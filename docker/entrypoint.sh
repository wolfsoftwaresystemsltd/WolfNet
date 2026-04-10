#!/bin/bash
set -e

# ─── WolfNet Docker Entrypoint ───
# Configures WolfNet from environment variables and starts the daemon.

CONFIG_DIR="/etc/wolfnet"
CONFIG_FILE="$CONFIG_DIR/config.toml"
KEY_FILE="$CONFIG_DIR/private.key"
STATUS_DIR="/var/run/wolfnet"

mkdir -p "$CONFIG_DIR" "$STATUS_DIR"

# ─── Check TUN device ───
if [ ! -c /dev/net/tun ]; then
    echo "ERROR: /dev/net/tun not found."
    echo "Run with: --device /dev/net/tun:/dev/net/tun"
    echo "If the TUN module is not loaded, run: modprobe tun"
    exit 1
fi

# ─── Generate keys if missing ───
if [ ! -f "$KEY_FILE" ]; then
    echo "Generating WolfNet key pair..."
    wolfnet genkey > "$KEY_FILE"
    chmod 600 "$KEY_FILE"
    echo "Key generated. Public key:"
    wolfnet pubkey < "$KEY_FILE"
fi

# ─── Handle join token ───
if [ -n "$WOLFNET_JOIN_TOKEN" ] && [ ! -f "$CONFIG_DIR/.joined" ]; then
    echo "Joining WolfNet network with invite token..."
    if wolfnet join "$WOLFNET_JOIN_TOKEN" --config-dir "$CONFIG_DIR"; then
        touch "$CONFIG_DIR/.joined"
        echo "Successfully joined. Check 'docker logs wolfnet' for the reverse token."
    else
        echo "WARNING: Join failed. Starting with existing config if available."
    fi
fi

# ─── Generate config from env vars if missing ───
if [ ! -f "$CONFIG_FILE" ]; then
    echo "Generating config from environment variables..."
    ADDR="${WOLFNET_ADDRESS:-10.0.10.1}"
    SUBNET="${WOLFNET_SUBNET:-24}"
    PORT="${WOLFNET_PORT:-9600}"
    IFACE="${WOLFNET_INTERFACE:-wolfnet0}"
    GW="${WOLFNET_GATEWAY:-false}"
    DISC="${WOLFNET_DISCOVERY:-true}"
    HOSTNAME="${WOLFNET_HOSTNAME:-$(hostname)}"

    cat > "$CONFIG_FILE" <<EOF
[node]
address = "$ADDR/$SUBNET"
listen_port = $PORT
interface = "$IFACE"
hostname = "$HOSTNAME"
gateway = $GW

[network]
discovery = $DISC
discovery_port = 9601
EOF

    # Add static peers if provided (JSON array)
    if [ -n "$WOLFNET_PEERS" ]; then
        echo "" >> "$CONFIG_FILE"
        echo "$WOLFNET_PEERS" | python3 -c "
import json, sys
peers = json.load(sys.stdin)
for p in peers:
    print(f'''
[[peers]]
public_key = \"{p.get('public_key', '')}\"
endpoint = \"{p.get('endpoint', '')}\"
allowed_ips = \"{p.get('allowed_ips', '10.0.10.0/24')}\"
''')
" >> "$CONFIG_FILE" 2>/dev/null || true
    fi

    echo "Config generated at $CONFIG_FILE"
fi

echo "Starting WolfNet daemon..."
echo "  Config: $CONFIG_FILE"
echo "  Interface: ${WOLFNET_INTERFACE:-wolfnet0}"
echo "  Port: ${WOLFNET_PORT:-9600}"

exec wolfnet --config "$CONFIG_FILE"
