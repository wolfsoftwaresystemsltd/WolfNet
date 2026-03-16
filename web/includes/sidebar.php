<?php
// $active should be set before including this file, e.g. $active = 'wolfstack.php';
$active = $active ?? '';
function nav_active($href, $active) {
    return ($href === $active) ? ' class="active"' : '';
}
?>
<nav class="topnav" id="topnav">
    <div class="topnav-inner">
        <a href="index.php" class="topnav-logo"><img src="images/wolfstack-logo.png" alt="WolfStack"></a>

        <button class="topnav-toggle" id="topnav-toggle" aria-label="Menu"><span></span><span></span><span></span></button>

        <div class="topnav-menu" id="topnav-menu">
            <!-- WolfStack mega dropdown -->
            <div class="topnav-item has-dropdown">
                <button class="topnav-link" type="button">WolfStack</button>
                <div class="dropdown-panel dropdown-mega">
                    <div class="dropdown-col">
                        <div class="dropdown-col-title">Getting Started</div>
                        <a href="wolfstack.php"<?= nav_active('wolfstack.php', $active) ?>>Overview &amp; Quick Start</a>
                        <a href="wolfstack-containers.php"<?= nav_active('wolfstack-containers.php', $active) ?>>Container Management</a>
                        <a href="wolfstack-clustering.php"<?= nav_active('wolfstack-clustering.php', $active) ?>>Multi-Server Clustering</a>
                        <a href="app-store.php"<?= nav_active('app-store.php', $active) ?>>App Store</a>
                        <a href="proxmox.php"<?= nav_active('proxmox.php', $active) ?>>Proxmox Integration</a>
                        <a href="wolfrun.php"<?= nav_active('wolfrun.php', $active) ?>>WolfRun Orchestration</a>
                        <a href="wolfkube.php"<?= nav_active('wolfkube.php', $active) ?>>WolfKube Kubernetes</a>
                    </div>
                    <div class="dropdown-col">
                        <div class="dropdown-col-title">Server Management</div>
                        <a href="wolfstack-storage.php"<?= nav_active('wolfstack-storage.php', $active) ?>>Storage &amp; Disks</a>
                        <a href="wolfstack-ceph.php"<?= nav_active('wolfstack-ceph.php', $active) ?>>Ceph Clusters</a>
                        <a href="wolfstack-networking.php"<?= nav_active('wolfstack-networking.php', $active) ?>>Networking</a>
                        <a href="wolfstack-files.php"<?= nav_active('wolfstack-files.php', $active) ?>>File Manager</a>
                        <a href="wolfstack-terminal.php"<?= nav_active('wolfstack-terminal.php', $active) ?>>Terminal</a>
                        <a href="wolfstack-tui.php"<?= nav_active('wolfstack-tui.php', $active) ?>>Terminal UI</a>
                        <a href="wolfstack-cron.php"<?= nav_active('wolfstack-cron.php', $active) ?>>Cron Jobs</a>
                    </div>
                    <div class="dropdown-col">
                        <div class="dropdown-col-title">Monitoring &amp; Tools</div>
                        <a href="wolfstack-alerting.php"<?= nav_active('wolfstack-alerting.php', $active) ?>>Alerting &amp; Notifications</a>
                        <a href="wolfstack-statuspage.php"<?= nav_active('wolfstack-statuspage.php', $active) ?>>Status Pages</a>
                        <a href="wolfstack-issues.php"<?= nav_active('wolfstack-issues.php', $active) ?>>Issues Scanner</a>
                        <a href="wolfstack-backups.php"<?= nav_active('wolfstack-backups.php', $active) ?>>Backup &amp; Restore</a>
                        <a href="wolfstack-mysql.php"<?= nav_active('wolfstack-mysql.php', $active) ?>>MariaDB/MySQL Editor</a>
                        <a href="wolfstack-ai.php"<?= nav_active('wolfstack-ai.php', $active) ?>>AI Agent</a>
                    </div>
                    <div class="dropdown-col">
                        <div class="dropdown-col-title">Security &amp; Network</div>
                        <a href="wolfstack-security.php"<?= nav_active('wolfstack-security.php', $active) ?>>Security</a>
                        <a href="wolfstack-certificates.php"<?= nav_active('wolfstack-certificates.php', $active) ?>>Certificates</a>
                        <a href="wolfnet-vpn.php"<?= nav_active('wolfnet-vpn.php', $active) ?>>Remote Access VPN</a>
                        <a href="wolfstack-wireguard.php"<?= nav_active('wolfstack-wireguard.php', $active) ?>>WireGuard Bridge</a>
                        <a href="wolfstack-settings.php"<?= nav_active('wolfstack-settings.php', $active) ?>>Settings</a>
                        <a href="wolfnet-global.php"<?= nav_active('wolfnet-global.php', $active) ?>>Global View</a>
                    </div>
                </div>
            </div>

            <!-- Products dropdown -->
            <div class="topnav-item has-dropdown">
                <button class="topnav-link" type="button">Products</button>
                <div class="dropdown-panel">
                    <a href="wolfnet.php"<?= nav_active('wolfnet.php', $active) ?>>WolfNet <span class="dropdown-desc">Private Network</span></a>
                    <a href="wolfdisk.php"<?= nav_active('wolfdisk.php', $active) ?>>WolfDisk <span class="dropdown-desc">Distributed Filesystem</span></a>
                    <a href="wolfproxy.php"<?= nav_active('wolfproxy.php', $active) ?>>WolfProxy <span class="dropdown-desc">Reverse Proxy</span></a>
                    <a href="wolfserve.php"<?= nav_active('wolfserve.php', $active) ?>>WolfServe <span class="dropdown-desc">Web Server</span></a>
                    <a href="quickstart.php"<?= nav_active('quickstart.php', $active) ?>>WolfScale <span class="dropdown-desc">Database Replication</span></a>
                </div>
            </div>

            <!-- WolfScale docs dropdown -->
            <div class="topnav-item has-dropdown">
                <button class="topnav-link" type="button">WolfScale Docs</button>
                <div class="dropdown-panel">
                    <a href="quickstart.php"<?= nav_active('quickstart.php', $active) ?>>Quick Start</a>
                    <a href="features.php"<?= nav_active('features.php', $active) ?>>Features</a>
                    <a href="architecture.php"<?= nav_active('architecture.php', $active) ?>>Architecture</a>
                    <a href="how-it-works.php"<?= nav_active('how-it-works.php', $active) ?>>How It Works</a>
                    <a href="load-balancer.php"<?= nav_active('load-balancer.php', $active) ?>>Load Balancer</a>
                    <a href="binlog.php"<?= nav_active('binlog.php', $active) ?>>Binlog Mode</a>
                    <a href="config.php"<?= nav_active('config.php', $active) ?>>Configuration</a>
                    <a href="performance.php"<?= nav_active('performance.php', $active) ?>>Performance</a>
                    <a href="cli.php"<?= nav_active('cli.php', $active) ?>>CLI Reference</a>
                    <a href="troubleshooting.php"<?= nav_active('troubleshooting.php', $active) ?>>Troubleshooting</a>
                </div>
            </div>

            <a href="comparison.php" class="topnav-link<?= ($active === 'comparison.php') ? ' active' : '' ?>">Compare</a>
            <a href="enterprise.php" class="topnav-link<?= ($active === 'enterprise.php') ? ' active' : '' ?>">Licensing</a>

            <!-- About dropdown -->
            <div class="topnav-item has-dropdown">
                <button class="topnav-link" type="button">About</button>
                <div class="dropdown-panel">
                    <a href="about.php"<?= nav_active('about.php', $active) ?>>About</a>
                    <a href="roadmap.php"<?= nav_active('roadmap.php', $active) ?>>Roadmap</a>
                    <a href="contact.php"<?= nav_active('contact.php', $active) ?>>Contact</a>
                    <a href="glossary.php"<?= nav_active('glossary.php', $active) ?>>Glossary</a>
                    <a href="licensing.php"<?= nav_active('licensing.php', $active) ?>>Licensing</a>
                    <a href="privacy.php"<?= nav_active('privacy.php', $active) ?>>Privacy Policy</a>
                    <a href="terms.php"<?= nav_active('terms.php', $active) ?>>Terms of Service</a>
                    <a href="supporters.php"<?= nav_active('supporters.php', $active) ?>>Supporters</a>
                    <a href="support.php"<?= nav_active('support.php', $active) ?> style="color:var(--accent-primary);font-weight:600;">Support Us</a>
                </div>
            </div>

            <div class="topnav-right-group">
                <a href="support.php" class="topnav-link" style="color:var(--accent-primary);font-weight:600;font-size:0.82rem;">&#10084; Support Us</a>
                <a href="https://github.com/wolfsoftwaresystemsltd/WolfScale" target="_blank" class="topnav-link topnav-icon-link" title="GitHub">GitHub</a>
                <a href="https://discord.gg/q9qMjHjUQY" target="_blank" class="topnav-link topnav-icon-link" title="Discord">Discord</a>
                <a href="https://www.youtube.com/@wolfsoftwaresystems" target="_blank" rel="noopener" class="topnav-social" title="YouTube"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.4.6A3 3 0 0 0 .5 6.2 31.4 31.4 0 0 0 0 12a31.4 31.4 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.6 9.4.6 9.4.6s7.5 0 9.4-.6a3 3 0 0 0 2.1-2.1A31.4 31.4 0 0 0 24 12a31.4 31.4 0 0 0-.5-5.8zM9.6 15.6V8.4l6.3 3.6-6.3 3.6z"/></svg></a>
                <a href="https://www.instagram.com/wolfsoftwaresystems/" target="_blank" rel="noopener" class="topnav-social" title="Instagram"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg></a>
                <a href="https://www.tiktok.com/@wolfsoftwaresystems" target="_blank" rel="noopener" class="topnav-social" title="TikTok"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg></a>
                <a href="https://www.reddit.com/r/WolfStack/" target="_blank" rel="noopener" class="topnav-social" title="Reddit"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm5.01 4.74c.69 0 1.25.56 1.25 1.25a1.25 1.25 0 0 1-2.5 0c0-.69.56-1.25 1.25-1.25zM12 6.25c1.67 0 3.19.44 4.42 1.18a1.87 1.87 0 0 1 2.78 1.63c0 .7-.38 1.3-.95 1.63.03.18.05.36.05.55 0 2.81-3.27 5.09-7.3 5.09s-7.3-2.28-7.3-5.09c0-.19.02-.37.04-.55a1.87 1.87 0 0 1-.94-1.63c0-1.04.84-1.88 1.88-1.88.5 0 .95.19 1.29.51A10.22 10.22 0 0 1 12 6.25zM8.5 11.5a1.25 1.25 0 1 0 0 2.5 1.25 1.25 0 0 0 0-2.5zm7 0a1.25 1.25 0 1 0 0 2.5 1.25 1.25 0 0 0 0-2.5zm-6.47 3.82a.31.31 0 0 1 .44 0c.71.71 1.93 1.05 2.53 1.05.6 0 1.82-.34 2.53-1.05a.31.31 0 0 1 .44.44c-.84.84-2.2 1.23-2.97 1.23-.77 0-2.13-.39-2.97-1.23a.31.31 0 0 1 0-.44z"/></svg></a>
                <button class="theme-toggle" id="theme-toggle" aria-label="Toggle theme"><span class="theme-toggle-slider"></span></button>
            </div>
        </div>
    </div>
</nav>
