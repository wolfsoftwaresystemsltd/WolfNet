# WolfNet Docker Container
# Secure mesh VPN — runs in --network host mode with /dev/net/tun
#
# Usage:
#   docker run -d --name wolfnet --network host \
#     --cap-add NET_ADMIN --device /dev/net/tun:/dev/net/tun \
#     -v wolfnet-config:/etc/wolfnet \
#     -e WOLFNET_JOIN_TOKEN=<token> \
#     ghcr.io/wolfsoftwaresystemsltd/wolfnet:latest

FROM debian:bookworm-slim

RUN apt-get update && apt-get install -y --no-install-recommends \
    iproute2 \
    iptables \
    procps \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /etc/wolfnet /var/run/wolfnet

COPY wolfnet /usr/local/bin/wolfnet
COPY wolfnetctl /usr/local/bin/wolfnetctl
COPY entrypoint.sh /entrypoint.sh

RUN chmod +x /usr/local/bin/wolfnet /usr/local/bin/wolfnetctl /entrypoint.sh

EXPOSE 9600/udp
EXPOSE 9601/udp

VOLUME ["/etc/wolfnet"]

ENTRYPOINT ["/entrypoint.sh"]
