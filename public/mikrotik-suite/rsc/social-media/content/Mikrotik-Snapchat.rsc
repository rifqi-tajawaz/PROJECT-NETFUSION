/ip firewall raw
rem [find address-list=Connection-Snapchat]
add action=add-dst-to-address-list address-list=Connection-Snapchat address-list-timeout=1d chain=prerouting content=snapchat.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION SNAPCHAT => {SOCIAL MEDIA}"
add action=add-dst-to-address-list address-list=Connection-Snapchat address-list-timeout=1d chain=prerouting content=.snapchat. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Snapchat"]
add address=104.193.184.0/24 list=Connection-Snapchat
add address=104.193.185.0/24 list=Connection-Snapchat
add address=204.154.248.0/24 list=Connection-Snapchat
add address=204.154.249.0/24 list=Connection-Snapchat
add address=204.154.250.0/24 list=Connection-Snapchat
add address=204.154.251.0/24 list=Connection-Snapchat
add address=204.154.252.0/22 list=Connection-Snapchat
