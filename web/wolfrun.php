<?php
$page_title = 'WolfRun Orchestration — WolfStack Docs';
$page_desc = 'WolfRun — native container orchestration for WolfStack. Schedule, scale, load-balance, and failover Docker & LXC services across your cluster with zero external dependencies.';
$page_keywords = 'WolfRun, container orchestration, Docker, LXC, scaling, failover, HA, load balancing, WolfStack, cluster scheduling, service mesh';
$active = 'wolfrun.php';
include 'includes/head.php';
?>
<body>
<div class="wiki-layout">
    <?php include 'includes/sidebar.php'; ?>
    <main class="wiki-content">

            <div class="content-section">
                <h1>Overview</h1>
                <p>WolfRun is WolfStack&rsquo;s native container orchestration engine. It lets you define <strong>services</strong> that are automatically scheduled, scaled, load-balanced, and managed across your cluster nodes &mdash; similar to Docker Swarm or Kubernetes Deployments, but built directly into WolfStack with support for both Docker <em>and</em> LXC containers.</p>
                <img src="screenshots/wolfrun.png" alt="WolfRun container orchestration dashboard" class="screenshot" loading="lazy">
                <p>WolfRun requires <strong>zero external dependencies</strong> &mdash; no etcd, no kubelet, no CNI plugins. Everything runs inside the WolfStack binary. Container networking is handled automatically by <a href="wolfnet.php">WolfNet</a>.</p>

                <h3>Key Features</h3>
                <ul>
                    <li><strong>Docker &amp; LXC support</strong> &mdash; Orchestrate both Docker containers and LXC system containers from the same interface</li>
                    <li><strong>Auto-scaling</strong> &mdash; Set min/max replicas and scale up or down with one click</li>
                    <li><strong>Built-in load balancing</strong> &mdash; Each service gets a WolfNet VIP with round-robin or IP-hash (sticky session) routing</li>
                    <li><strong>Container failover (HA)</strong> &mdash; Pre-stage standby containers on other nodes; if a node goes down, standby containers are promoted automatically</li>
                    <li><strong>Cross-node cloning</strong> &mdash; Clone and migrate LXC containers across nodes with automatic WolfNet IP assignment</li>
                    <li><strong>Rolling updates</strong> &mdash; Update Docker images with zero-downtime rolling deployments</li>
                    <li><strong>Placement strategies</strong> &mdash; Schedule on any node, prefer a specific node, or pin to a required node</li>
                    <li><strong>Restart policies</strong> &mdash; Always, OnFailure, or Never &mdash; WolfRun automatically restarts stopped containers</li>
                    <li><strong>Adopt existing containers</strong> &mdash; Bring existing Docker or LXC containers under WolfRun management without recreating them</li>
                    <li><strong>Port forwarding</strong> &mdash; Forward external ports on your server to a service&rsquo;s WolfNet VIP</li>
                    <li><strong>App Store integration</strong> &mdash; Deploy applications from the WolfStack App Store directly into WolfRun</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>How WolfRun Works</h2>
                <p>WolfRun uses a <strong>leader-based reconciliation loop</strong> that runs every 15 seconds. The cluster leader (lowest alphabetical node ID among online nodes) is responsible for all orchestration decisions:</p>
                <ol>
                    <li><strong>Check state</strong> &mdash; The leader queries every node in the cluster for the actual state of each container (running, stopped, lost, offline)</li>
                    <li><strong>Scale up</strong> &mdash; If there are fewer active instances than the desired replica count, WolfRun clones or creates new containers on eligible nodes</li>
                    <li><strong>Scale down</strong> &mdash; If there are more running instances than desired, excess instances are removed (preferring nodes with the most instances to maintain spread)</li>
                    <li><strong>Restart</strong> &mdash; If a container has exited or stopped and the restart policy is &ldquo;Always&rdquo;, WolfRun restarts it automatically</li>
                    <li><strong>Clean up</strong> &mdash; Instances that have been &ldquo;lost&rdquo; (container vanished from an online node) for more than 5 minutes are removed and replaced</li>
                    <li><strong>Failover</strong> &mdash; If failover is enabled, the leader detects offline nodes and promotes standby containers</li>
                    <li><strong>Manage standby</strong> &mdash; Creates stopped standby containers on nodes that don&rsquo;t already have an instance</li>
                    <li><strong>Update load balancer</strong> &mdash; Rebuilds iptables DNAT rules so the service VIP routes to all healthy backends</li>
                </ol>
                <p>This loop is <strong>leader-only</strong> and uses an atomic lock to prevent concurrent runs. If the leader goes offline, the next node in alphabetical order takes over within 15 seconds.</p>
            </div>

            <div class="content-section">
                <h2>Creating a Service</h2>
                <p>Navigate to the <strong>WolfRun</strong> page in the datacenter sidebar and click <strong>Deploy Service</strong>.</p>

                <h3>Docker Service</h3>
                <ol>
                    <li>Enter a <strong>service name</strong> (e.g. &ldquo;Web Frontend&rdquo;)</li>
                    <li>Set the <strong>runtime</strong> to Docker</li>
                    <li>Enter the <strong>Docker image</strong> (e.g. <code>nginx:latest</code>, <code>redis:7</code>, <code>ghcr.io/your-org/your-app:v2</code>)</li>
                    <li>Set the number of <strong>replicas</strong> (how many instances to run)</li>
                    <li>Add <strong>port mappings</strong> (one per line, e.g. <code>80:80</code> or <code>3000:3000</code>)</li>
                    <li>Add <strong>environment variables</strong> (one per line, e.g. <code>DATABASE_URL=postgres://...</code>)</li>
                    <li>Add <strong>volume mounts</strong> (one per line, e.g. <code>/data:/app/data</code>)</li>
                    <li>Choose a <strong>restart policy</strong> (Always, OnFailure, or Never)</li>
                    <li>Optionally enable <strong>Container Failover</strong> for high availability</li>
                    <li>Click <strong>Deploy</strong></li>
                </ol>
                <p>WolfRun will pull the image on the target node, create the container, start it, assign a WolfNet IP, and register it as a managed instance. If you requested multiple replicas, containers are spread across eligible cluster nodes.</p>

                <h3>LXC Service</h3>
                <ol>
                    <li>Enter a <strong>service name</strong></li>
                    <li>Set the <strong>runtime</strong> to LXC</li>
                    <li>Choose the <strong>distribution</strong> (Ubuntu, Debian, Alpine, AlmaLinux, etc.)</li>
                    <li>Choose the <strong>release</strong> (e.g. jammy, bookworm, 3.19)</li>
                    <li>Choose the <strong>architecture</strong> (amd64, arm64, armhf, i386)</li>
                    <li>Set the number of <strong>replicas</strong></li>
                    <li>Click <strong>Deploy</strong></li>
                </ol>
                <p>WolfRun creates the first LXC container from the distribution template, then <strong>clones</strong> it across nodes for additional replicas. Each clone gets a unique bridge IP and WolfNet IP automatically.</p>

                <div class="warning-box" style="margin-top:1rem;">
                    <p><strong>LXC cross-node cloning:</strong> When WolfRun needs to place an LXC replica on a different node, it performs a full rootfs clone-and-migrate over the network. This can take 30&ndash;120 seconds depending on the container size and network speed. The instance shows as &ldquo;pending&rdquo; in the dashboard until the migration completes.</p>
                </div>
            </div>

            <div class="content-section">
                <h2>Service Management</h2>

                <h3>Dashboard</h3>
                <p>The WolfRun dashboard shows all services with real-time status:</p>
                <ul>
                    <li><strong>Status badge</strong> &mdash; Healthy (all replicas running), Scaling (under-provisioned), Degraded (some instances offline), Down (no instances running), or Idle</li>
                    <li><strong>Replica count</strong> &mdash; Running/desired with a progress bar and min/max range</li>
                    <li><strong>Instance list</strong> &mdash; Click a service row to expand and see every instance with container name, node, WolfNet IP, and status. Standby instances are shown with a purple STANDBY badge at reduced opacity</li>
                    <li><strong>Service VIP</strong> &mdash; The WolfNet virtual IP assigned to this service for load-balanced access</li>
                </ul>

                <h3>Scaling</h3>
                <p>Scale services up or down directly from the dashboard:</p>
                <ul>
                    <li>Click the <strong>+</strong> or <strong>&minus;</strong> buttons for quick scale-up/down</li>
                    <li>Open <strong>Settings</strong> to set precise desired, minimum, and maximum replica counts</li>
                    <li>WolfRun respects the min/max bounds &mdash; you cannot scale below the minimum or above the maximum</li>
                    <li>Scale-up creates new containers on the least-loaded eligible nodes</li>
                    <li>Scale-down removes instances from nodes with the most instances, maintaining spread</li>
                </ul>

                <h3>Actions</h3>
                <p>Bulk actions apply to all <strong>active</strong> instances (standby instances are skipped):</p>
                <ul>
                    <li><strong>Start All</strong> &mdash; Start all stopped active instances</li>
                    <li><strong>Stop All</strong> &mdash; Stop all running active instances</li>
                    <li><strong>Restart All</strong> &mdash; Restart all active instances</li>
                </ul>

                <h3>Settings</h3>
                <p>The service settings modal lets you configure:</p>
                <ul>
                    <li><strong>Desired replicas</strong> &mdash; Target number of running instances</li>
                    <li><strong>Min replicas</strong> &mdash; Scale-down floor (WolfRun will never remove instances below this count)</li>
                    <li><strong>Max replicas</strong> &mdash; Scale-up ceiling</li>
                    <li><strong>Load balancer policy</strong> &mdash; Round Robin or IP Hash (sticky sessions)</li>
                    <li><strong>Allowed nodes</strong> &mdash; Restrict which cluster nodes can run instances of this service. If all or none are checked, all nodes are eligible</li>
                    <li><strong>Container failover</strong> &mdash; Enable or disable standby container pre-staging (see <a href="#failover">Failover</a> below)</li>
                </ul>

                <h3>Rolling Updates (Docker)</h3>
                <p>For Docker services, you can update the container image without downtime:</p>
                <ol>
                    <li>Click the <strong>Update</strong> button on a service</li>
                    <li>Enter the new image tag (e.g. <code>myapp:v2.1</code>)</li>
                    <li>WolfRun pulls the new image on each node and replaces instances one at a time</li>
                </ol>
                <p>The load balancer continues routing traffic to healthy instances while the update rolls out.</p>

                <h3>Deleting a Service</h3>
                <p>Deleting a service stops and destroys all cloned instances (containers with &ldquo;wolfrun&rdquo; in their name) and all standby instances. The original template container is preserved so you can re-deploy or use it standalone.</p>
            </div>

            <div class="content-section">
                <h2>Load Balancing</h2>
                <p>Every WolfRun service is automatically assigned a <strong>WolfNet Virtual IP (VIP)</strong> from the <code>10.10.10.x</code> range. The VIP acts as a stable endpoint that load-balances traffic across all healthy instances.</p>

                <h3>How It Works</h3>
                <ol>
                    <li>When a service is created, WolfStack allocates a unique WolfNet IP as the service VIP</li>
                    <li>The VIP is added as a local route on the leader node</li>
                    <li>iptables DNAT rules in the <code>PREROUTING</code> and <code>OUTPUT</code> chains redirect traffic destined for the VIP to the backend container IPs</li>
                    <li>The reconciliation loop updates the backend list every 15 seconds &mdash; only <strong>running, non-standby</strong> instances with a WolfNet IP are included</li>
                    <li>The VIP is announced on WolfNet via gratuitous ARP so other nodes can route to it</li>
                </ol>

                <h3>Load Balancer Policies</h3>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Policy</th>
                                <th>Behaviour</th>
                                <th>Best For</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Round Robin</strong></td>
                                <td>Distributes connections evenly across backends using iptables <code>nth</code> statistic mode</td>
                                <td>Stateless services (web servers, APIs, workers)</td>
                            </tr>
                            <tr>
                                <td><strong>IP Hash</strong></td>
                                <td>Routes connections from the same source IP to the same backend (sticky sessions via <code>hashlimit</code>)</td>
                                <td>Stateful services (session-based apps, WebSocket servers)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Port Forwarding</h3>
                <p>By default, the service VIP is only accessible from your WolfNet mesh. To expose a service externally, use the <strong>Port Forward</strong> button on a service to create iptables rules that forward a port on your server&rsquo;s public IP to the service VIP.</p>

                <h3>Example</h3>
                <div class="code-block" style="margin:0.5rem 0 1rem 0;">
                    <div class="code-header"><span class="code-lang">example</span></div>
                    <pre><code>Service "Web App" with 3 replicas, VIP = 10.10.10.42

  Instance 1  &rarr;  10.10.10.15 (node-a)  &rarr;  running
  Instance 2  &rarr;  10.10.10.16 (node-b)  &rarr;  running
  Instance 3  &rarr;  10.10.10.17 (node-c)  &rarr;  running

  curl http://10.10.10.42:80
    &rarr; iptables DNAT &rarr; 10.10.10.15 (1st request)
    &rarr; iptables DNAT &rarr; 10.10.10.16 (2nd request)
    &rarr; iptables DNAT &rarr; 10.10.10.17 (3rd request)
    &rarr; round-robin repeats...</code></pre>
                </div>
            </div>

            <div class="content-section" id="failover">
                <h2>Container Failover (HA)</h2>
                <p>WolfRun&rsquo;s failover system provides <strong>automatic high availability</strong> for your services. When enabled, WolfRun pre-creates stopped &ldquo;standby&rdquo; containers on other cluster nodes. If a node hosting active containers goes offline, standby containers are instantly promoted &mdash; no re-imaging, no IP reassignment, no manual intervention.</p>

                <h3>How Failover Works</h3>
                <ol>
                    <li><strong>Enable failover</strong> on a service (either during deployment or in the service settings)</li>
                    <li>WolfRun automatically <strong>creates standby containers</strong> on cluster nodes that don&rsquo;t already have an instance of this service. For Docker, this means pulling the image and creating the container (stopped). For LXC, this means cloning the template (stopped)</li>
                    <li>Each standby gets a <strong>pre-allocated WolfNet IP</strong> so there&rsquo;s no IP assignment delay during promotion</li>
                    <li>If a node with active containers goes offline, the cluster leader <strong>detects the failure</strong> within 15 seconds and <strong>promotes standby containers</strong> on surviving nodes &mdash; starting them and marking them as active</li>
                    <li>A <strong>failover event</strong> is logged with the timestamp, service name, source node, destination node, and details</li>
                </ol>

                <h3>What Happens During Failover</h3>
                <div class="code-block" style="margin:0.5rem 0 1rem 0;">
                    <div class="code-header"><span class="code-lang">failover scenario</span></div>
                    <pre><code>Before:
  node-a (online)   &rarr;  myapp-wolfrun-abc123  [RUNNING]
  node-b (online)   &rarr;  myapp-standby-def456  [STANDBY, stopped]
  node-c (online)   &rarr;  myapp-standby-ghi789  [STANDBY, stopped]

node-a goes offline...

After (within 15 seconds):
  node-a (offline)  &rarr;  myapp-wolfrun-abc123  [removed from tracking]
  node-b (online)   &rarr;  myapp-standby-def456  [RUNNING, promoted]
  node-c (online)   &rarr;  myapp-standby-ghi789  [STANDBY, stopped]

Failover event logged:
  "Standby 'myapp-standby-def456' promoted &mdash; replacing failed 'myapp-wolfrun-abc123'"

Next cycle: WolfRun creates a new standby on node-c (or another eligible node)
to restore full HA coverage.</code></pre>
                </div>

                <h3>Key Details</h3>
                <ul>
                    <li><strong>Node-agnostic</strong> &mdash; Any surviving cluster leader can detect failures and promote standby. There is no single point of failure in the failover mechanism itself</li>
                    <li><strong>No shared storage required</strong> &mdash; Each standby is a full copy of the container. Application-level clustering (database replication, session stores, etc.) handles data consistency</li>
                    <li><strong>Standby containers are excluded</strong> from active replica counts, load balancer backends, restart policies, and bulk start/stop/restart actions. They sit idle until needed</li>
                    <li><strong>Failover events</strong> are persisted to disk and shown in the WolfRun dashboard as a log table with timestamps, service names, source/destination nodes, and details</li>
                    <li><strong>Disabling failover</strong> on a service automatically stops, destroys, and removes all its orphaned standby containers on the next reconciliation cycle</li>
                </ul>

                <h3>When to Use Failover</h3>
                <ul>
                    <li><strong>Stateless web apps and APIs</strong> &mdash; Instant recovery with no data concerns</li>
                    <li><strong>Databases with replication</strong> &mdash; Standby database containers can rejoin the replication cluster when promoted</li>
                    <li><strong>Critical services</strong> &mdash; Any service where downtime is unacceptable and you have spare capacity on other nodes</li>
                </ul>
                <p>Failover works best when you have <strong>3 or more nodes</strong> in your cluster, so there are always spare nodes available for standby placement.</p>
            </div>

            <div class="content-section">
                <h2>Adopting Existing Containers</h2>
                <p>You can bring an existing Docker or LXC container under WolfRun management without recreating it:</p>
                <ol>
                    <li>Navigate to the container in the WolfStack dashboard</li>
                    <li>Click <strong>Adopt into WolfRun</strong></li>
                    <li>Enter a service name &mdash; the container becomes the first instance of a new WolfRun service</li>
                    <li>WolfRun registers the container, assigns a service VIP, and begins managing it</li>
                </ol>
                <p>Once adopted, you can scale the service to create additional replicas cloned from the original container. The original container is never destroyed &mdash; even if you later delete the WolfRun service, the original template is preserved.</p>
            </div>

            <div class="content-section">
                <h2>Placement &amp; Scheduling</h2>
                <p>WolfRun uses a <strong>spread scheduler</strong> that distributes instances across nodes to maximise availability. The scheduler scores each eligible node based on:</p>
                <ul>
                    <li><strong>CPU usage</strong> &mdash; Lower CPU usage scores better</li>
                    <li><strong>Memory usage</strong> &mdash; Lower memory usage scores better</li>
                    <li><strong>Instance count</strong> &mdash; Nodes that already run instances of this service are penalised (spread factor)</li>
                </ul>
                <p>Three placement strategies are available:</p>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Strategy</th>
                                <th>Behaviour</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Any</strong> (default)</td>
                                <td>Schedule on any eligible node, spreading instances across the cluster for best availability</td>
                            </tr>
                            <tr>
                                <td><strong>Prefer Node</strong></td>
                                <td>Try the preferred node first; fall back to other nodes if it&rsquo;s unavailable or at capacity</td>
                            </tr>
                            <tr>
                                <td><strong>Require Node</strong></td>
                                <td>Only schedule on the specified node. If it&rsquo;s offline or full, the service cannot scale</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Node Eligibility</h3>
                <p>A node is eligible to receive a WolfRun instance if:</p>
                <ul>
                    <li>It is <strong>online</strong></li>
                    <li>It is in the <strong>same cluster</strong> as the service</li>
                    <li>It has the required <strong>runtime installed</strong> (Docker or LXC)</li>
                    <li>It is in the service&rsquo;s <strong>allowed nodes</strong> list (if one is configured)</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Networking</h2>
                <p>WolfRun relies on <a href="wolfnet.php">WolfNet</a> for all container networking:</p>
                <ul>
                    <li>Every instance gets a <strong>WolfNet IP</strong> on the <code>10.10.10.x</code> subnet, making it reachable from any node in your cluster</li>
                    <li>The service <strong>VIP</strong> is also a WolfNet IP that load-balances across healthy instances</li>
                    <li>Cross-node cloning automatically assigns unique WolfNet IPs to avoid conflicts</li>
                    <li>WolfNet routes are announced via gratuitous ARP and pushed into the WolfNet routing table so all peers can reach the service</li>
                </ul>
                <p>If WolfNet is not configured, containers will only have their local bridge IP and cross-node access will not work. Setting up WolfNet before using WolfRun is strongly recommended.</p>
            </div>

            <div class="content-section">
                <h2>WolfRun vs. WolfKube</h2>
                <p>WolfStack includes two orchestration systems for different use cases:</p>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Feature</th>
                                <th>WolfRun</th>
                                <th>WolfKube</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Technology</td><td>WolfStack-native orchestration</td><td>Kubernetes (k3s, MicroK8s, kubeadm, k0s, RKE2)</td></tr>
                            <tr><td>Container Runtime</td><td>Docker &amp; LXC</td><td>containerd</td></tr>
                            <tr><td>Setup</td><td>No setup &mdash; built into WolfStack</td><td>Provision via WolfKube wizard</td></tr>
                            <tr><td>Complexity</td><td>Simple, zero-config</td><td>More complex but industry-standard</td></tr>
                            <tr><td>LXC Support</td><td style="color:var(--success);">Yes</td><td>No</td></tr>
                            <tr><td>Container Failover</td><td style="color:var(--success);">Built-in standby HA</td><td>Kubernetes pod rescheduling</td></tr>
                            <tr><td>Load Balancing</td><td>WolfNet VIP (iptables DNAT)</td><td>WolfNet private IPs + Kubernetes Service routing</td></tr>
                            <tr><td>Networking</td><td>WolfNet mesh</td><td>Kubernetes CNI + WolfNet</td></tr>
                            <tr><td>Ecosystem</td><td>WolfStack-only</td><td>Full Kubernetes ecosystem (Helm, operators, CRDs)</td></tr>
                            <tr><td>Best For</td><td>Quick scaling, LXC orchestration, simple deployments, self-hosted apps</td><td>Production workloads, Helm charts, industry-standard tooling</td></tr>
                        </tbody>
                    </table>
                </div>
                <p>You can run both WolfRun and WolfKube on the same cluster. Use WolfRun for quick deployments, LXC-based services, and simple scaling. Use WolfKube when you need the full Kubernetes ecosystem.</p>
            </div>

            <div class="content-section">
                <h2>REST API</h2>
                <p>WolfRun is fully controllable via the REST API. All endpoints require authentication via the <code>wolfstack_session</code> cookie (browser) or <code>X-WolfStack-Secret</code> header (inter-node).</p>
                <div class="table-wrapper">
                    <table class="data-table" style="min-width:650px;">
                        <thead>
                            <tr>
                                <th>Method</th>
                                <th>Endpoint</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td><code>GET</code></td><td><code>/api/wolfrun/services</code></td><td>List all services (filter by <code>?cluster=name</code>)</td></tr>
                            <tr><td><code>POST</code></td><td><code>/api/wolfrun/services</code></td><td>Create a new service</td></tr>
                            <tr><td><code>GET</code></td><td><code>/api/wolfrun/services/{id}</code></td><td>Get a single service with all instances</td></tr>
                            <tr><td><code>DELETE</code></td><td><code>/api/wolfrun/services/{id}</code></td><td>Delete a service and destroy cloned containers</td></tr>
                            <tr><td><code>POST</code></td><td><code>/api/wolfrun/services/{id}/scale</code></td><td>Scale a service (<code>{"replicas": 5}</code>)</td></tr>
                            <tr><td><code>POST</code></td><td><code>/api/wolfrun/services/{id}/settings</code></td><td>Update settings (min/max replicas, LB policy, failover, allowed nodes)</td></tr>
                            <tr><td><code>POST</code></td><td><code>/api/wolfrun/services/{id}/action</code></td><td>Start, stop, or restart all active instances</td></tr>
                            <tr><td><code>POST</code></td><td><code>/api/wolfrun/services/{id}/update</code></td><td>Rolling update &mdash; change the Docker image</td></tr>
                            <tr><td><code>POST</code></td><td><code>/api/wolfrun/services/adopt</code></td><td>Adopt an existing container into WolfRun</td></tr>
                            <tr><td><code>GET</code></td><td><code>/api/wolfrun/failover-events</code></td><td>List failover events (filter by <code>?service_id=id</code>)</td></tr>
                            <tr><td><code>GET</code></td><td><code>/api/wolfrun/services/{id}/portforward</code></td><td>List port forwarding rules for a service</td></tr>
                            <tr><td><code>POST</code></td><td><code>/api/wolfrun/services/{id}/portforward</code></td><td>Add a port forwarding rule</td></tr>
                            <tr><td><code>DELETE</code></td><td><code>/api/wolfrun/services/{id}/portforward/{rule}</code></td><td>Remove a port forwarding rule</td></tr>
                        </tbody>
                    </table>
                </div>

                <h3>Example: Create a Service</h3>
                <div class="code-block" style="margin:0.5rem 0 1rem 0;">
                    <div class="code-header"><span class="code-lang">bash</span></div>
                    <pre><code>curl -k -b "wolfstack_session=YOUR_SESSION" \
  -X POST https://your-server:8553/api/wolfrun/services \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Web App",
    "image": "nginx:latest",
    "runtime": "docker",
    "replicas": 3,
    "cluster_name": "MyCluster",
    "ports": ["80:80"],
    "env": ["NGINX_HOST=example.com"],
    "restart_policy": "always",
    "failover": true
  }'</code></pre>
                </div>

                <h3>Example: Scale a Service</h3>
                <div class="code-block" style="margin:0.5rem 0 1rem 0;">
                    <div class="code-header"><span class="code-lang">bash</span></div>
                    <pre><code>curl -k -b "wolfstack_session=YOUR_SESSION" \
  -X POST https://your-server:8553/api/wolfrun/services/svc-a1b2c3d4/scale \
  -H "Content-Type: application/json" \
  -d '{"replicas": 5}'</code></pre>
                </div>
            </div>

            <div class="content-section">
                <h2>Configuration &amp; Persistence</h2>
                <p>WolfRun stores all state as JSON files in <code>/etc/wolfstack/wolfrun/</code>:</p>
                <ul>
                    <li><code>services.json</code> &mdash; All service definitions, replica counts, and instance state</li>
                    <li><code>failover-events.json</code> &mdash; Failover event log (last 100 events)</li>
                </ul>
                <p>State is loaded on startup and saved automatically on every change. The leader node broadcasts its service state to all cluster peers so every node has a consistent view.</p>
                <p>There is no database &mdash; everything is file-based. You can back up the <code>/etc/wolfstack/wolfrun/</code> directory to preserve your WolfRun configuration.</p>
            </div>

            <div class="content-section">
                <h2>Troubleshooting</h2>

                <h3>Service stuck in &ldquo;Scaling&rdquo;</h3>
                <ul>
                    <li>Check that there are eligible nodes with the correct runtime (Docker or LXC) installed and online</li>
                    <li>Check the WolfStack logs (<code>journalctl -u wolfstack</code>) for &ldquo;WolfRun: no eligible nodes&rdquo; messages</li>
                    <li>Verify the service&rsquo;s allowed nodes list isn&rsquo;t excluding all nodes</li>
                    <li>For Docker: verify the image exists and is pullable from the target node</li>
                </ul>

                <h3>LXC clone fails</h3>
                <ul>
                    <li>Ensure the source container exists and is accessible</li>
                    <li>Cross-node clones require SSH access between nodes. WolfStack handles this automatically if nodes are in the same cluster</li>
                    <li>Check disk space on both source and target nodes &mdash; LXC clones copy the full rootfs</li>
                    <li>Check logs for &ldquo;Clone-migrate failed&rdquo; errors with specific details</li>
                </ul>

                <h3>Load balancer not working</h3>
                <ul>
                    <li>Verify WolfNet is running on the leader node (<code>ip addr show wolfnet0</code>)</li>
                    <li>Check that instances have WolfNet IPs assigned (visible in the instance list)</li>
                    <li>Verify iptables rules exist: <code>iptables -t nat -L PREROUTING -n | grep &lt;VIP&gt;</code></li>
                    <li>Ensure IP forwarding is enabled: <code>sysctl net.ipv4.ip_forward</code> should return <code>1</code></li>
                </ul>

                <h3>Failover not triggering</h3>
                <ul>
                    <li>Verify failover is enabled on the service (check the HA badge or settings modal)</li>
                    <li>Ensure there are standby instances visible in the instance list</li>
                    <li>The current node must be the cluster leader (lowest alphabetical node ID among online nodes)</li>
                    <li>Check that the failed node is actually detected as offline by the cluster (node polling runs every 10 seconds)</li>
                    <li>Check the failover events log at the bottom of the WolfRun page</li>
                </ul>
            </div>

<div class="page-nav"><a href="wolfstack-backups.php" class="prev">&larr; Backup &amp; Restore</a><a href="wolfkube.php" class="next">WolfKube Kubernetes &rarr;</a></div>

    </main>
<?php include 'includes/footer.php'; ?>
