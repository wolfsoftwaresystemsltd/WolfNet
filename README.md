# 🐺 WolfNet

**Secure private mesh networking over the internet.**

WolfNet creates encrypted tunnels between machines using TUN interfaces, X25519 key exchange, and ChaCha20-Poly1305 encryption. It automatically builds a mesh network with peer exchange (PEX), so joining one node gives you access to the entire network.

## Features

- **End-to-end encryption** — X25519 + ChaCha20-Poly1305
- **Mesh networking** — automatic peer exchange (PEX)
- **NAT traversal** — works behind firewalls and NAT
- **LAN auto-discovery** — finds peers on your local network
- **Gateway mode** — route internet traffic through a gateway node
- **Relay support** — reach peers through intermediate nodes
- **DynDNS support** — endpoints can be hostnames, re-resolved periodically
- **Invite system** — simple `wolfnet invite` / `wolfnet join` workflow
- **Zero dependencies at runtime** — single static binary

## Quick Install

```bash
curl -sSL https://raw.githubusercontent.com/wolfsoftwaresystemsltd/WolfNet/main/setup.sh | sudo bash
```

## Build from Source

```bash
git clone https://github.com/wolfsoftwaresystemsltd/WolfNet.git
cd WolfNet
cargo build --release
```

Binaries will be in `target/release/`:
- `wolfnet` — the daemon
- `wolfnetctl` — CLI status/management tool

## Usage

```bash
# Generate keys and create config
sudo wolfnet init --address 10.10.10.1

# Start the daemon
sudo wolfnet --config /etc/wolfnet/config.toml

# Generate an invite for another node
sudo wolfnet invite

# Join a network using an invite token
sudo wolfnet join <token>

# Check status
wolfnetctl status
wolfnetctl peers
```

## Configuration

Default config location: `/etc/wolfnet/config.toml`

```toml
[network]
interface = "wolfnet0"
address = "10.10.10.1"
subnet = 24
listen_port = 9600
gateway = false
discovery = false
mtu = 1400

[security]
private_key_file = "/etc/wolfnet/private.key"

[[peers]]
public_key = "base64_encoded_public_key"
endpoint = "1.2.3.4:9600"
allowed_ip = "10.10.10.2"
name = "server2"
```

## Systemd Service

The installer creates a systemd service automatically. Manual management:

```bash
sudo systemctl status wolfnet
sudo systemctl restart wolfnet
sudo journalctl -u wolfnet -f
```

## Documentation

For full documentation, visit **[wolfstack.org](https://wolfstack.org)**.

## License

[FSL-1.1-Apache-2.0](LICENSE) — Copyright 2024-2026 Wolf Software Systems Ltd.

## Part of WolfStack

WolfNet is the networking layer of [WolfStack](https://github.com/wolfsoftwaresystemsltd/WolfStack), a server management platform. It can also be used standalone.
