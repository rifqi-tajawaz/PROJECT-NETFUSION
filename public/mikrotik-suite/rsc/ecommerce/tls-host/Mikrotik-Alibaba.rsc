/ip firewall raw
rem [find address-list=Connection-Alibaba]
add action=add-dst-to-address-list address-list=Connection-Alibaba address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*alibaba.com* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION ALIBABA => {MARKETPLACE}"
add action=add-dst-to-address-list address-list=Connection-Alibaba address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.alibaba.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Alibaba"]
add address=3.0.0.0/10 list=Connection-Alibaba
add address=3.128.0.0/10 list=Connection-Alibaba
add address=34.192.0.0/10 list=Connection-Alibaba
add address=52.0.0.0/11 list=Connection-Alibaba
add address=54.192.0.0/12 list=Connection-Alibaba
add address=35.112.0.0/12 list=Connection-Alibaba
add address=47.74.0.0/17 list=Connection-Alibaba
add address=47.236.0.0/16 list=Connection-Alibaba
add address=47.240.0.0/16 list=Connection-Alibaba
add address=47.242.0.0/16 list=Connection-Alibaba
add address=59.82.0.0/16 list=Connection-Alibaba
add address=39.96.0.0/14 list=Connection-Alibaba
