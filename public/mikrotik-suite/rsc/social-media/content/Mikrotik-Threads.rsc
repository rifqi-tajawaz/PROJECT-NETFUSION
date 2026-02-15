/ip firewall raw
rem [find address-list=Connection-Threads]
add action=add-dst-to-address-list address-list=Connection-Threads address-list-timeout=1d chain=prerouting content=threads.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION THREADS => {SOCIAL MEDIA}"
add action=add-dst-to-address-list address-list=Connection-Threads address-list-timeout=1d chain=prerouting content=threads.net dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Threads address-list-timeout=1d chain=prerouting content=.threads. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Threads"]
add address=31.13.24.0/21 list=Connection-Threads
add address=31.13.64.0/18 list=Connection-Threads
add address=45.64.40.0/22 list=Connection-Threads
add address=66.220.144.0/21 list=Connection-Threads
add address=66.220.152.0/21 list=Connection-Threads
add address=69.63.176.0/21 list=Connection-Threads
add address=69.63.184.0/21 list=Connection-Threads
add address=74.119.76.0/22 list=Connection-Threads
add address=102.132.96.0/20 list=Connection-Threads
add address=103.4.96.0/22 list=Connection-Threads
add address=129.134.0.0/16 list=Connection-Threads
add address=157.240.0.0/17 list=Connection-Threads
add address=163.70.128.0/17 list=Connection-Threads
add address=163.114.128.0/20 list=Connection-Threads
add address=173.252.64.0/19 list=Connection-Threads
add address=179.60.192.0/22 list=Connection-Threads
add address=185.60.216.0/22 list=Connection-Threads
add address=185.89.216.0/22 list=Connection-Threads
add address=204.15.20.0/22 list=Connection-Threads
add address=157.240.0.63 list=Connection-Threads
