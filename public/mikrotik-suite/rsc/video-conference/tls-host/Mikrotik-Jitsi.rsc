/ip firewall raw
rem [find address-list=Connection-Jitsi]
add action=add-dst-to-address-list address-list=Connection-Jitsi address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*jitsi.org dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION JITSI  => {VIDEO CONPERENCE}"
add action=add-dst-to-address-list address-list=Connection-Jitsi address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.jitsi.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Jitsi"]
