/ip firewall raw
rem [find address-list=Connection-Facebook]
add action=add-dst-to-address-list address-list=Connection-Facebook address-list-timeout=1d chain=prerouting content=facebook.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION FACEBOOK => {SOCIAL MEDIA}"
add action=add-dst-to-address-list address-list=Connection-Facebook address-list-timeout=1d chain=prerouting content=.facebook. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Facebook address-list-timeout=1d chain=prerouting content=.m.me dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Facebook address-list-timeout=1d chain=prerouting content=.fb.me dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Facebook address-list-timeout=1d chain=prerouting content=.msngr. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Facebook address-list-timeout=1d chain=prerouting content=.messenger. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Facebook address-list-timeout=1d chain=prerouting content=.fbcdn. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Facebook address-list-timeout=1d chain=prerouting content=.fbsbx. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Facebook"]
add address=31.13.24.0/21 list=Connection-Facebook
add address=31.13.64.0/18 list=Connection-Facebook
add address=45.64.40.0/22 list=Connection-Facebook
add address=66.220.144.0/21 list=Connection-Facebook
add address=66.220.152.0/21 list=Connection-Facebook
add address=69.63.176.0/21 list=Connection-Facebook
add address=69.63.184.0/21 list=Connection-Facebook
add address=74.119.76.0/22 list=Connection-Facebook
add address=102.132.96.0/20 list=Connection-Facebook
add address=103.4.96.0/22 list=Connection-Facebook
add address=129.134.0.0/16 list=Connection-Facebook
add address=157.240.0.0/17 list=Connection-Facebook
add address=163.70.128.0/17 list=Connection-Facebook
add address=163.114.128.0/20 list=Connection-Facebook
add address=173.252.64.0/19 list=Connection-Facebook
add address=179.60.192.0/22 list=Connection-Facebook
add address=185.60.216.0/22 list=Connection-Facebook
add address=185.89.216.0/22 list=Connection-Facebook
add address=204.15.20.0/22 list=Connection-Facebook
