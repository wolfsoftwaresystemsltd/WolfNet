#!/bin/bash
set -e

# ─── WolfNet Docker Entrypoint ────────────────────────────────────────────────
# First-run bootstrap, then exec the daemon. The WolfNet binary creates
# config.toml and the private key itself on `init` / `join`, so this script
# just picks which of those to run on first boot.

CONFIG="/etc/wolfnet/config.toml"
CONFIG_DIR="$(dirname "$CONFIG")"
STATUS_DIR="/var/run/wolfnet"

mkdir -p "$CONFIG_DIR" "$STATUS_DIR"

if [ ! -c /dev/net/tun ]; then
    echo "✗ /dev/net/tun not found inside the container."
    echo "  Run with:  --device /dev/net/tun:/dev/net/tun"
    echo "  If the module is not loaded on the host:  sudo modprobe tun"
    exit 1
fi

if [ ! -f "$CONFIG" ]; then
    if [ -n "$WOLFNET_JOIN_TOKEN" ]; then
        echo "→ Joining WolfNet using supplied invite token..."
        wolfnet --config "$CONFIG" join "$WOLFNET_JOIN_TOKEN"
    else
        ADDRESS="${WOLFNET_ADDRESS:-10.0.10.1}"
        echo "→ No config and no join token — initialising new network at $ADDRESS"
        echo "  (run 'docker exec wolfnet wolfnet invite' to get a token for peers)"
        wolfnet --config "$CONFIG" init --address "$ADDRESS"
    fi
fi

echo "→ Starting WolfNet daemon..."
exec wolfnet --config "$CONFIG" "$@"
