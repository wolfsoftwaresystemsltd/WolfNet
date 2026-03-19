//! wolfnetctl — CLI utility for WolfNet status and management
//!
//! Usage:
//!   wolfnetctl status          - Show node status
//!   wolfnetctl list servers    - List all servers on the network
//!   wolfnetctl peers           - Show detailed peer info
//!   wolfnetctl info            - Show full network summary

use std::path::PathBuf;
use clap::{Parser, Subcommand};

/// Status file location (written by wolfnet daemon)
const STATUS_FILE: &str = "/var/run/wolfnet/status.json";

#[derive(Parser)]
#[command(name = "wolfnetctl", version, about = "WolfNet control utility")]
struct Cli {
    #[command(subcommand)]
    command: Commands,
}

#[derive(Subcommand)]
enum Commands {
    /// Show this node's status
    Status,
    /// List servers, peers, etc.
    List {
        #[command(subcommand)]
        what: ListSubcommand,
    },
    /// List all peers on the network with hostnames
    Peers,
    /// Show network summary
    Info,
    /// Purge stale/ghost peers — reloads config and removes any peers not in config.toml
    Purge,
}

#[derive(Subcommand)]
enum ListSubcommand {
    /// List all servers on the WolfNet network
    Servers,
}

#[derive(serde::Deserialize)]
struct NodeStatus {
    hostname: String,
    address: String,
    public_key: String,
    listen_port: u16,
    gateway: bool,
    interface: String,
    uptime_secs: u64,
    peers: Vec<PeerStatus>,
}

#[derive(serde::Deserialize)]
#[allow(dead_code)]
struct PeerStatus {
    hostname: String,
    address: String,
    endpoint: String,
    public_key: String,
    last_seen_secs: u64,
    rx_bytes: u64,
    tx_bytes: u64,
    connected: bool,
    #[serde(default)]
    relay_via: Option<String>,
    #[serde(default)]
    is_gateway: bool,
}

fn main() {
    let cli = Cli::parse();
    let status = load_status();

    match cli.command {
        Commands::Status => cmd_status(&status),
        Commands::List { what } => match what {
            ListSubcommand::Servers => cmd_list_servers(&status),
        },
        Commands::Peers => cmd_peers(&status),
        Commands::Info => cmd_info(&status),
        Commands::Purge => cmd_purge(),
    }
}

fn load_status() -> NodeStatus {
    let path = PathBuf::from(STATUS_FILE);
    if !path.exists() {
        eprintln!("Error: WolfNet daemon is not running (no status file at {})", STATUS_FILE);
        eprintln!("Start the daemon with: sudo wolfnet");
        std::process::exit(1);
    }
    let content = std::fs::read_to_string(&path).unwrap_or_else(|e| {
        eprintln!("Error reading status file: {}", e);
        std::process::exit(1);
    });
    serde_json::from_str(&content).unwrap_or_else(|e| {
        eprintln!("Error parsing status: {}", e);
        std::process::exit(1);
    })
}

fn cmd_status(status: &NodeStatus) {
    println!();
    println!("  🐺 WolfNet Status");
    println!("  ─────────────────────────────────────");
    println!("  Hostname:    {}", status.hostname);
    println!("  WolfNet IP:  {}", status.address);
    println!("  Interface:   {}", status.interface);
    println!("  Listen Port: {}", status.listen_port);
    println!("  Gateway:     {}", if status.gateway { "Yes" } else { "No" });
    println!("  Public Key:  {}...{}", &status.public_key[..8], &status.public_key[status.public_key.len()-4..]);
    println!("  Uptime:      {}", format_duration(status.uptime_secs));
    println!("  Peers:       {} ({} connected)",
        status.peers.len(),
        status.peers.iter().filter(|p| p.connected).count(),
    );
    println!();
}

fn cmd_list_servers(status: &NodeStatus) {
    // Count active nodes
    let connected_peers = status.peers.iter()
        .filter(|p| p.connected)
        .count();
    let total_nodes = status.peers.len() + 1;
    let active_nodes = connected_peers + 1;

    println!();
    println!("  WolfNet Network");
    println!("  {}", "─".repeat(50));
    println!();
    println!("  Nodes {} active / {} total", active_nodes, total_nodes);
    println!();
    println!("  {:20} {:25} {:10}", "NODE", "ADDRESS", "ROLE");
    println!("  {:20} {:25} {:10}", "─".repeat(18), "─".repeat(23), "─".repeat(8));

    // Print this node first
    let my_role = if status.gateway { "Gateway" } else { "Node" };
    println!("  {:20} {:25} {:10}",
        format!("● {} (self)", status.hostname),
        format!("{}:{}", status.address, status.listen_port),
        my_role);

    // Print peers
    for peer in &status.peers {
        let indicator = if peer.connected { "●" } else { "○" };
        let role = if peer.is_gateway { "Gateway" } else { "Node" };
        let name = format!("{} {}", indicator, peer.hostname);
        let addr = format!("{}:{}", peer.address, status.listen_port);
        println!("  {:20} {:25} {:10}", name, addr, role);
    }

    println!();
}

fn cmd_peers(status: &NodeStatus) {
    if status.peers.is_empty() {
        println!("No peers configured.");
        println!("Add peers via /etc/wolfnet/config.toml or enable LAN discovery.");
        return;
    }

    println!();
    println!("  🐺 WolfNet Peers");
    println!("  ─────────────────────────────────────────────────────────────────────");
    println!("  {:<16} {:<16} {:<24} {:<10} {}",
        "HOSTNAME", "WOLFNET IP", "ENDPOINT", "STATUS", "LAST SEEN");
    println!("  ─────────────────────────────────────────────────────────────────────");

    for peer in &status.peers {
        let status_str = if peer.connected {
            "online".to_string()
        } else if peer.relay_via.is_some() {
            format!("via {}", peer.relay_via.as_deref().unwrap_or("?"))
        } else {
            "offline".to_string()
        };
        let status_icon = if peer.connected { "●" } else if peer.relay_via.is_some() { "◉" } else { "○" };
        let last_seen = if peer.last_seen_secs == u64::MAX {
            "never".to_string()
        } else {
            format_duration(peer.last_seen_secs)
        };
        let host = if peer.hostname.is_empty() { "-" } else { &peer.hostname };
        println!("  {:<16} {:<16} {:<24} {} {:<14} {}",
            host, peer.address, peer.endpoint, status_icon, status_str, last_seen);
    }

    // Traffic summary
    let total_rx: u64 = status.peers.iter().map(|p| p.rx_bytes).sum();
    let total_tx: u64 = status.peers.iter().map(|p| p.tx_bytes).sum();
    println!();
    println!("  Traffic: ↓ {} received  ↑ {} sent", format_bytes(total_rx), format_bytes(total_tx));
    println!();
}

fn cmd_info(status: &NodeStatus) {
    cmd_status(status);
    if !status.peers.is_empty() {
        cmd_peers(status);
    }
}

fn cmd_purge() {
    println!();
    println!("  🧹 Purging stale peers...");
    println!();

    // Delete routes.json to clear stale route entries
    let routes_path = "/var/run/wolfnet/routes.json";
    if std::path::Path::new(routes_path).exists() {
        match std::fs::remove_file(routes_path) {
            Ok(_) => println!("  ✓ Deleted {}", routes_path),
            Err(e) => println!("  ✗ Could not delete {}: {}", routes_path, e),
        }
    }

    // Send SIGHUP to wolfnet daemon to reload config and purge non-configured peers
    let pid_output = std::process::Command::new("pidof")
        .arg("wolfnet")
        .output();
    match pid_output {
        Ok(output) if output.status.success() => {
            let pid_str = String::from_utf8_lossy(&output.stdout).trim().to_string();
            // pidof may return multiple PIDs — take the first
            if let Some(pid) = pid_str.split_whitespace().next() {
                match std::process::Command::new("kill")
                    .args(["-HUP", pid])
                    .status()
                {
                    Ok(s) if s.success() => {
                        println!("  ✓ Sent SIGHUP to wolfnet (PID {})", pid);
                        println!("    Daemon will reload config and remove peers not in config.toml");
                    }
                    _ => println!("  ✗ Failed to send SIGHUP to PID {}", pid),
                }
            } else {
                println!("  ✗ WolfNet daemon not found — is it running?");
            }
        }
        _ => println!("  ✗ WolfNet daemon not found — is it running?"),
    }

    println!();
    println!("  Run 'wolfnetctl peers' to verify the ghost peers are gone.");
    println!();
}

fn format_duration(secs: u64) -> String {
    if secs < 60 { return format!("{}s", secs); }
    if secs < 3600 { return format!("{}m {}s", secs / 60, secs % 60); }
    if secs < 86400 { return format!("{}h {}m", secs / 3600, (secs % 3600) / 60); }
    format!("{}d {}h", secs / 86400, (secs % 86400) / 3600)
}

fn format_bytes(bytes: u64) -> String {
    if bytes < 1024 { return format!("{} B", bytes); }
    if bytes < 1024 * 1024 { return format!("{:.1} KB", bytes as f64 / 1024.0); }
    if bytes < 1024 * 1024 * 1024 { return format!("{:.1} MB", bytes as f64 / (1024.0 * 1024.0)); }
    format!("{:.2} GB", bytes as f64 / (1024.0 * 1024.0 * 1024.0))
}
