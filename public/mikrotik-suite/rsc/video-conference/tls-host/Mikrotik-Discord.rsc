/ip firewall raw
rem [find address-list=Connection-Discord]
add action=add-dst-to-address-list address-list=Connection-Discord address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*discord.com* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION DISCORD => {VIDEO CONPERENCE}"
add action=add-dst-to-address-list address-list=Connection-Discord address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.discord.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Discord"]
add address=66.22.192.0/18 list=Connection-Discord
add address=66.22.196.0/22 list=Connection-Discord
add address=66.22.200.0/22 list=Connection-Discord
add address=66.22.204.0/22 list=Connection-Discord
add address=66.22.208.0/22 list=Connection-Discord
add address=66.22.228.0/23 list=Connection-Discord
add address=66.22.220.0/23 list=Connection-Discord
add address=162.159.128.233 list=Connection-Discord
add address=162.159.130.235 list=Connection-Discord
