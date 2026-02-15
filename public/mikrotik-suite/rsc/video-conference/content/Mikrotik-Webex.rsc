/ip firewall raw
rem [find address-list=Connection-Webex]
add action=add-dst-to-address-list address-list=Connection-Webex address-list-timeout=1d chain=prerouting content=webex.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION WEBEX => {VIDEO CONPERENCE}"
add action=add-dst-to-address-list address-list=Connection-Webex address-list-timeout=1d chain=prerouting content=.webex. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Webex"]
add address=23.89.0.0/16 list=Connection-Webex
add address=62.109.192.0/18 list=Connection-Webex
add address=64.68.96.0/19 list=Connection-Webex
add address=66.114.160.0/20 list=Connection-Webex
add address=66.163.32.0/19 list=Connection-Webex
add address=69.26.160.0/19 list=Connection-Webex
add address=114.29.192.0/19 list=Connection-Webex
add address=144.196.0.0/16 list=Connection-Webex
add address=163.129.0.0/16 list=Connection-Webex
add address=170.72.0.0/16 list=Connection-Webex
add address=170.133.128.0/18 list=Connection-Webex
add address=173.39.224.0/19 list=Connection-Webex
add address=173.243.0.0/20 list=Connection-Webex
add address=207.182.160.0/19 list=Connection-Webex
add address=209.197.192.0/19 list=Connection-Webex
add address=210.4.192.0/20 list=Connection-Webex
add address=216.151.128.0/19 list=Connection-Webex
add address=150.253.128.0/18 list=Connection-Webex
add address=170.72.128.0/18 list=Connection-Webex
add address=144.196.128.0/19 list=Connection-Webex
add address=150.253.192.0/19 list=Connection-Webex
add address=163.129.48.0/20 list=Connection-Webex
add address=163.129.64.0/20 list=Connection-Webex
add address=69.26.176.0/20 list=Connection-Webex
add address=139.177.84.0/24 list=Connection-Webex
