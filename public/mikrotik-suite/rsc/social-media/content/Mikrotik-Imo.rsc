/ip firewall raw
rem [find address-list=Connection-Imo]
add action=add-dst-to-address-list address-list=Connection-Imo address-list-timeout=1d chain=prerouting content=imo.im dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION IMO => {SOCIAL MEDIA}"
add action=add-dst-to-address-list address-list=Connection-Imo address-list-timeout=1d chain=prerouting content=.imo. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Imo"]
add address=103.65.37.0/24 list=Connection-Imo
add address=172.217.194.0/24 list=Connection-Imo
add address=103.65.38.0/24 list=Connection-Imo
add address=103.211.229.0/24 list=Connection-Imo
add address=185.22.42.0/24 list=Connection-Imo
add address=192.12.31.0/24 list=Connection-Imo
add address=103.208.253.0/24 list=Connection-Imo
add address=54.72.242.0/24 list=Connection-Imo
add address=139.99.3.0/24 list=Connection-Imo
add address=52.17.115.0/24 list=Connection-Imo
add address=182.0.177.0/24 list=Connection-Imo
add address=74.125.68.0/24 list=Connection-Imo
add address=10.19.196.0/24 list=Connection-Imo
