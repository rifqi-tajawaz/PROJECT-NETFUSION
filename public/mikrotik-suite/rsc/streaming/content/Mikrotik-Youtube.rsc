/ip firewall raw
rem [find address-list=Connection-Youtube]
add action=add-dst-to-address-list address-list=Connection-Youtube address-list-timeout=1d chain=prerouting content=youtube.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION YOUTUBE => {STREAMING}"
add action=add-dst-to-address-list address-list=Connection-Youtube address-list-timeout=1d chain=prerouting content=.youtube. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Youtube address-list-timeout=1d chain=prerouting content=.googlevideo. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Youtube address-list-timeout=1d chain=prerouting content=.youtu. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Youtube address-list-timeout=1d chain=prerouting content=.ytimg. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Youtube address-list-timeout=1d chain=prerouting content=yt3.ggpht.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Youtube address-list-timeout=1d chain=prerouting content=youtubei.googleapis.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Youtube"]
add address=64.18.0.0/20 list=Connection-Youtube
add address=64.233.160.0/19 list=Connection-Youtube
add address=66.102.0.0/20 list=Connection-Youtube
add address=66.249.80.0/20 list=Connection-Youtube
add address=72.14.192.0/18 list=Connection-Youtube
add address=74.125.0.0/16 list=Connection-Youtube
add address=173.194.0.0/16 list=Connection-Youtube
add address=207.126.144.0/20 list=Connection-Youtube
add address=209.85.128.0/17 list=Connection-Youtube
add address=216.58.208.0/20 list=Connection-Youtube
add address=216.239.32.0/19 list=Connection-Youtube
