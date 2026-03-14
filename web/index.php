<?php
$page_title = 'WolfStack — The Universal Server Management Platform';
$page_desc = 'WolfStack — The Universal Server Management Platform. Monitor servers, manage Kubernetes, Docker & LXC containers, Virtual Machines, networking, storage, and more from a beautiful web dashboard.';
$page_keywords = 'server management, WolfStack, dashboard, Kubernetes, Docker, LXC, monitoring, clustering, WolfScale, WolfDisk, WolfNet, Proxmox alternative, server dashboard, container management, k3s, WolfKube';
$page_canonical = 'https://wolfscale.org/';
$active = 'index.php';

// Load setup URL copy count
$_copyCountFile = __DIR__ . '/data/copy-counts.json';
$_setupCopies = 0;
if (file_exists($_copyCountFile)) {
    $_counts = json_decode(file_get_contents($_copyCountFile), true);
    if (isset($_counts['setup-url']['count'])) {
        $_setupCopies = (int)$_counts['setup-url']['count'];
    }
}

include 'includes/head.php';
?>
<body>
<div class="wiki-layout">
    <?php include 'includes/sidebar.php'; ?>

    <main class="wiki-main">
        <div class="wiki-content" style="max-width:100%;">

            <!-- Hero -->
            <section class="hp-hero">
                <img src="images/wolfstack-logo.png" alt="WolfStack" class="hp-hero-logo">
                <h1 class="hp-hero-headline">
                    The universal server management platform
                </h1>
                <p class="hp-hero-sub">
                    Monitor, manage, and orchestrate your entire infrastructure from a single dashboard.
                    Kubernetes, Docker &amp; LXC containers, VMs, networking, storage, backups, and more &mdash;
                    built in Rust for speed and reliability. Install on top of Proxmox to bring your
                    existing VE clusters into WolfStack&rsquo;s unified management, create native
                    WolfStack clusters, or mix and match.
                </p>
                <p class="hp-hero-meta">
                    Open source &middot; FSL-1.1 License &middot; Free for personal use &middot;
                    <a href="enterprise.php">Enterprise licensing available</a>
                </p>
                <?php if ($_setupCopies >= 10000): ?>
                <p class="hp-hero-meta" style="margin-top:0.5rem;font-weight:600;">
                    <?= number_format($_setupCopies) ?> installs and counting
                </p>
                <?php endif; ?>
            </section>

            <!-- Supported Distros -->
            <div style="display:grid;grid-template-columns:repeat(4,auto);justify-content:center;gap:1rem 2.5rem;padding:1.25rem 0;margin:0 auto;max-width:900px;">
                <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.4rem;">
                    <img src="images/distros/ubuntu.svg" alt="" style="width:20px;height:20px;"> Ubuntu
                </span>
                <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.4rem;">
                    <img src="images/distros/debian.svg" alt="" style="width:20px;height:20px;"> Debian
                </span>
                <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.4rem;">
                    <img src="images/distros/fedora.svg" alt="" style="width:20px;height:20px;"> Fedora
                </span>
                <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.4rem;">
                    <img src="images/distros/rhel.svg" alt="" style="width:20px;height:20px;"> RHEL / CentOS
                </span>
                <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.4rem;">
                    <img src="images/distros/opensuse.svg" alt="" style="width:20px;height:20px;"> openSUSE / SLES
                </span>
                <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.4rem;">
                    <img src="images/distros/arch.svg" alt="" style="width:20px;height:20px;"> Arch Linux
                </span>
                <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.4rem;">
                    <img src="images/distros/proxmox.svg" alt="" style="width:20px;height:20px;"> Proxmox VE
                </span>
                <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.4rem;">
                    <img src="images/distros/raspberrypi.svg" alt="" style="width:20px;height:20px;"> Raspberry Pi
                </span>
            </div>

            <!-- Screenshot -->
            <div class="hp-screenshot">
                <img src="images/screenshots/hero-dashboard-2x.png" alt="WolfStack Dashboard — datacenter overview with infrastructure map, server room view, and real-time metrics">
            </div>

            <!-- Quick Start -->
            <section class="hp-section">
                <h2 class="hp-section-title">Quick Start</h2>
                <div>
                    <ol style="text-align:left;color:var(--text-secondary);font-size:0.86rem;line-height:1.8;padding-left:1.25rem;">
                        <li><strong>Install WolfStack on each computer you want to use it on</strong> &mdash; this can be everything from a Raspberry Pi, desktop computer, up to a server or VPS hosted or in the cloud:
                            <div class="code-block" style="margin:0.5rem 0;">
                                <div class="code-header"><span>bash</span><button class="copy-btn" data-track="setup-url" onclick="copyCode(this)">Copy</button></div>
                                <pre><code>curl -sSL https://raw.githubusercontent.com/wolfsoftwaresystemsltd/WolfStack/master/setup.sh | sudo bash</code></pre>
                            </div>
                            <p style="font-size:0.8rem;color:var(--text-muted);margin:0.5rem 0 0;line-height:1.7;">
                                If <code>sudo</code> or <code>curl</code> are not installed, install them first:<br>
                                <strong>Debian / Ubuntu:</strong> <code>apt install sudo curl</code><br>
                                <strong>RHEL / Fedora:</strong> <code>dnf install sudo curl</code><br>
                                <strong>Arch Linux:</strong> <code>pacman -S sudo curl</code><br>
                                <strong>openSUSE / SLES:</strong> <code>zypper install sudo curl</code><br><br>
                                <strong>Low disk space?</strong> (Raspberry Pi, etc.) Build on an external drive:<br>
                                <code>curl -sSL ...setup.sh | sudo bash -s -- --install-dir /mnt/usb</code>
                            </p>
                        </li>
                        <li><strong>Get the token</strong> from each server &mdash; after installation, each server displays its cluster token. You can also run <code>wolfstack --show-token</code></li>
                        <li><strong>Open the web UI</strong> on <strong>one</strong> server &mdash; navigate to <code>https://your-server-ip:8553</code> and log in. You only need to log in to one server.</li>
                        <li><strong>Add your other nodes</strong> &mdash; click the <strong>+</strong> button to add each server or Proxmox server. You're done!</li>
                        <li><strong>Update WolfNet connections</strong> &mdash; go into your cluster settings and click <strong>Update WolfNet Connections</strong> to automatically set up peer-to-peer networking between all your nodes.</li>
                    </ol>
                    <p style="color:var(--text-secondary);font-size:0.82rem;margin-top:0.75rem;">
                        <strong>Note:</strong> You can add multiple WolfStack or Proxmox clusters to WolfStack's dashboard for a one-stop server management shop.
                    </p>
                </div>
                <div class="warning-box" style="margin-top:1rem;">
                    <p><strong>Rust Compilation:</strong> WolfStack compiles from source during installation. On low-powered devices like Raspberry Pi, the first build compiles all ~330 crates and can take <strong>30&ndash;60 minutes</strong>. The installer automatically creates temporary swap space to prevent out-of-memory failures. Subsequent upgrades only recompile WolfStack itself and take just a few minutes.</p>
                </div>
            </section>

            <!-- Key capabilities -->
            <section class="hp-section">
                <h2 class="hp-section-title">Everything you need in one platform</h2>
                <p class="hp-section-sub">WolfStack replaces a patchwork of tools with a single, unified dashboard.</p>

                <div class="hp-features">
                    <div class="hp-feature">
                        <h3>Container Management</h3>
                        <p>Create, clone, migrate, and manage Docker and LXC containers across your entire fleet. Built-in App Store for one-click deployments.</p>
                    </div>
                    <div class="hp-feature">
                        <h3>Multi-Server Clustering</h3>
                        <p>Add any number of servers to a cluster. Real-time CPU, memory, disk, and network metrics with interactive graphs for every node.</p>
                    </div>
                    <div class="hp-feature">
                        <h3>Encrypted Mesh Network</h3>
                        <p>WolfNet creates an encrypted private network between all your servers automatically. Works across data centres, cloud providers, and home labs.</p>
                    </div>
                    <div class="hp-feature">
                        <h3>Kubernetes Management</h3>
                        <p>Provision and manage k3s, MicroK8s, kubeadm, k0s, and RKE2 clusters. Full pod management, deployments, storage, and private WolfNet load balancing.</p>
                    </div>
                    <div class="hp-feature">
                        <h3>Proxmox &amp; VM Integration</h3>
                        <p>Install WolfStack on top of Proxmox VE. It auto-detects your cluster and manages VMs and containers from one interface.</p>
                    </div>
                    <div class="hp-feature">
                        <h3>Storage &amp; Disks</h3>
                        <p>Disk partitioning, formatting, SMART health monitoring, GParted-style visuals, Ceph cluster management, and S3/NFS mounts.</p>
                    </div>
                    <div class="hp-feature">
                        <h3>Status Pages &amp; Alerting</h3>
                        <p>Built-in uptime monitoring with public status pages. Alerting via Discord, Slack, Telegram, and email when things go wrong.</p>
                    </div>
                    <div class="hp-feature">
                        <h3>Container Failover (HA)</h3>
                        <p>WolfRun pre-stages standby containers on other nodes. If a node goes down, standby containers are automatically promoted — zero-downtime failover with no shared storage required.</p>
                    </div>
                </div>
            </section>

            <!-- Screenshots -->
            <section class="hp-section">
                <h2 class="hp-section-title">See it in action</h2>
                <div class="hp-screenshots-grid">
                    <div class="hp-ss-card">
                        <img src="images/node-detail.png" alt="Node Monitoring — real-time CPU, memory, disk, and network metrics">
                        <div class="hp-ss-caption">
                            <h3>Node Monitoring</h3>
                            <p>Real-time CPU, memory, disk, and network metrics with interactive graphs</p>
                        </div>
                    </div>
                    <div class="hp-ss-card">
                        <img src="images/app-store.png" alt="App Store — deploy containers and applications with one click">
                        <div class="hp-ss-caption">
                            <h3>App Store</h3>
                            <p>Deploy containers and applications to any node with one click</p>
                        </div>
                    </div>
                    <div class="hp-ss-card">
                        <img src="images/settings-themes.png" alt="Theme Engine — multiple built-in themes including dark and light modes">
                        <div class="hp-ss-caption">
                            <h3>Theme Engine</h3>
                            <p>Multiple beautiful themes &mdash; Dark, Midnight, Glass, Amber Terminal, and more</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Comparison -->
            <section class="hp-section">
                <h2 class="hp-section-title">How does WolfStack compare?</h2>
                <p class="hp-section-sub">One platform instead of six separate tools.</p>
                <div class="table-wrapper">
                    <table class="data-table" style="min-width:800px;">
                        <thead>
                            <tr>
                                <th style="text-align:left;">Feature</th>
                                <th style="text-align:center;color:var(--accent-primary);">WolfStack</th>
                                <th style="text-align:center;">Proxmox</th>
                                <th style="text-align:center;">Kubernetes</th>
                                <th style="text-align:center;">Portainer</th>
                                <th style="text-align:center;">CasaOS</th>
                                <th style="text-align:center;">Cockpit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Docker Containers</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;">Limited</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;">Plugin</td></tr>
                            <tr><td>LXC Containers</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>VM Management</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">Basic</td></tr>
                            <tr><td>Container Orchestration</td><td class="check" style="text-align:center;">WolfRun</td><td style="text-align:center;">No</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;">Swarm</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>Container Failover (HA)</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;">HA (paid)</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;">Swarm HA</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>Kubernetes Management</td><td class="check" style="text-align:center;">WolfKube</td><td style="text-align:center;">No</td><td style="text-align:center;">Native</td><td style="text-align:center;">Paid</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>Pod Terminal &amp; Monitoring</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;">CLI only</td><td style="text-align:center;">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>K8s Storage (PVCs)</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;">CLI only</td><td style="text-align:center;">Paid</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>Multi-Server Clustering</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;">Paid</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>Encrypted Mesh Network</td><td class="check" style="text-align:center;">WolfNet</td><td style="text-align:center;">No</td><td style="text-align:center;">CNI plugins</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>Built-in VPN</td><td class="check" style="text-align:center;">WolfNet VPN</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>Web Terminal</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;color:var(--success);">Yes</td></tr>
                            <tr><td>File Manager</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;">No</td></tr>
                            <tr><td>App Store</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;">Helm</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;">No</td></tr>
                            <tr><td>AI Agent</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>Status Pages &amp; Alerting</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;">Add-ons</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>Database Editor</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>Disk Partitioning &amp; SMART</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">Basic</td></tr>
                            <tr><td>Ceph Management</td><td class="check" style="text-align:center;">Yes</td><td style="text-align:center;color:var(--success);">Yes</td><td style="text-align:center;">Rook</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td><td style="text-align:center;">No</td></tr>
                            <tr><td>Install Complexity</td><td class="check" style="text-align:center;">1 command</td><td style="text-align:center;">ISO install</td><td style="text-align:center;">Very complex</td><td style="text-align:center;">Moderate</td><td style="text-align:center;color:var(--success);">1 command</td><td style="text-align:center;color:var(--success);">1 command</td></tr>
                            <tr><td>Written In</td><td class="check" style="text-align:center;">Rust</td><td style="text-align:center;">Perl/C</td><td style="text-align:center;">Go</td><td style="text-align:center;">Go</td><td style="text-align:center;">Go</td><td style="text-align:center;">Python/C</td></tr>
                            <tr><td>Price</td><td class="check" style="text-align:center;">Free &amp; Open Source</td><td style="text-align:center;">Free + Paid</td><td style="text-align:center;">Free</td><td style="text-align:center;">Free + Paid</td><td style="text-align:center;">Free</td><td style="text-align:center;">Free</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- The Wolf Toolkit -->
            <section class="hp-section">
                <h2 class="hp-section-title">The Wolf Toolkit</h2>
                <p class="hp-section-sub">Everything you need to build robust, clustered server infrastructure</p>

                <div class="hp-products">
                    <a href="wolfstack.php" class="hp-product hp-product-flagship">
                        <h3>WolfStack <span class="hp-product-tag hp-tag-flagship">Flagship</span></h3>
                        <p>The central management platform. Dashboard, containers, monitoring, clustering, App Store, Status Pages, AI Agent, and more.</p>
                    </a>
                    <a href="proxmox.php" class="hp-product">
                        <h3>Proxmox Integration <span class="hp-product-tag hp-tag-builtin">Built In</span></h3>
                        <p>Auto-detects Proxmox VE. Manage your entire virtualisation cluster from the WolfStack dashboard.</p>
                    </a>
                    <a href="wolfnet.php" class="hp-product">
                        <h3>WolfNet</h3>
                        <p>Encrypted mesh networking with built-in VPN. Connect servers across data centres as if they were on the same LAN.</p>
                    </a>
                    <a href="wolfdisk.php" class="hp-product">
                        <h3>WolfDisk</h3>
                        <p>Distributed filesystem with content-addressed deduplication. Mount shared directories across your network.</p>
                    </a>
                    <a href="wolfproxy.php" class="hp-product">
                        <h3>WolfProxy</h3>
                        <p>NGINX-compatible reverse proxy with a built-in firewall. Drop-in replacement &mdash; reads your existing config.</p>
                    </a>
                    <a href="wolfkube.php" class="hp-product">
                        <h3>WolfKube <span class="hp-product-tag hp-tag-builtin">Built In</span></h3>
                        <p>Kubernetes cluster management. Provision k3s, MicroK8s, kubeadm, and more. Full pod management with terminal, logs, processes, disk, and resource metrics. Persistent storage, deployment management, and private WolfNet load balancer.</p>
                    </a>
                    <a href="wolfserve.php" class="hp-product">
                        <h3>WolfServe</h3>
                        <p>Apache2-compatible web server with PHP via FastCGI. Reads your existing vhost configs directly.</p>
                    </a>
                    <a href="quickstart.php" class="hp-product">
                        <h3>WolfScale</h3>
                        <p>Database replication and load balancing. Keep MariaDB and MySQL synchronised across any number of servers.</p>
                    </a>
                </div>
            </section>

            <!-- CTA -->
            <section class="hp-cta">
                <h2>Ready to simplify your infrastructure?</h2>
                <p>One command to install. No containers, no dependencies &mdash; just a single binary.<br>
                    Free forever for personal use. <a href="enterprise.php">Enterprise licensing</a> available for businesses.</p>
                <div class="hp-cta-actions">
                    <a href="wolfstack.php" class="btn btn-primary">Get Started</a>
                    <a href="https://www.patreon.com/15362110/join" target="_blank" class="btn btn-secondary" style="border-color:var(--success);color:var(--success);">Support on Patreon</a>
                    <a href="enterprise.php" class="btn btn-secondary">Enterprise Licensing</a>
                </div>
                <div class="hp-community">
                    <a href="https://discord.gg/q9qMjHjUQY" target="_blank">Discord</a>
                    <a href="https://www.reddit.com/r/WolfStack/" target="_blank">Reddit</a>
                    <a href="https://www.youtube.com/@wolfsoftwaresystems" target="_blank">YouTube</a>
                    <a href="https://github.com/wolfsoftwaresystemsltd/WolfScale" target="_blank">GitHub</a>
                    <a href="https://opensimsocial.com/@lonewolf" target="_blank" rel="me">Mastodon</a>
                </div>
            </section>

            <!-- Documentation links -->
            <section class="hp-section">
                <h2 class="hp-section-title" style="font-size:1.1rem;color:var(--text-secondary);">Documentation</h2>
                <div class="docs-grid" style="max-width:700px;margin:0 auto;">
                    <a href="wolfstack.php" class="doc-card"><h3>WolfStack</h3><p>Overview</p></a>
                    <a href="quickstart.php" class="doc-card"><h3>Quick Start</h3><p>Install &amp; setup</p></a>
                    <a href="wolfstack-containers.php" class="doc-card"><h3>Containers</h3><p>Docker &amp; LXC</p></a>
                    <a href="wolfnet.php" class="doc-card"><h3>WolfNet</h3><p>Private network</p></a>
                    <a href="features.php" class="doc-card"><h3>Features</h3><p>Full list</p></a>
                </div>
            </section>

            <div class="page-nav">
                <span></span>
                <a href="wolfstack.php">WolfStack Overview &rarr;</a>
            </div>
        </div>

<?php include 'includes/footer.php'; ?>
