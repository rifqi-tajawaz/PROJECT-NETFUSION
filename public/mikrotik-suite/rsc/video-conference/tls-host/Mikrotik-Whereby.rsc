/ip firewall raw
rem [find address-list=Connection-Whereby]
add action=add-dst-to-address-list address-list=Connection-Whereby address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*whereby.org* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION WHEREBY => {VIDEO CONPERENCE}"
add action=add-dst-to-address-list address-list=Connection-Whereby address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.whereby.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Whereby"]
