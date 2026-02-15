/ip firewall raw
rem [find address-list=Connection-Vimeo]
add action=add-dst-to-address-list address-list=Connection-Vimeo address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*vimeo.com* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION VIMEO => {STREAMING}"
add action=add-dst-to-address-list address-list=Connection-Vimeo address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.vimeo.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Vimeo"]
add address=103.229.76.0/24 list=Connection-Vimeo
add address=149.72.131.0/24 list=Connection-Vimeo
add address=149.72.151.0/24 list=Connection-Vimeo
add address=149.72.155.0/24 list=Connection-Vimeo
add address=149.72.163.0/24 list=Connection-Vimeo
add address=149.72.180.0/24 list=Connection-Vimeo
add address=149.72.184.0/24 list=Connection-Vimeo
add address=149.72.206.0/24 list=Connection-Vimeo
add address=34.143.194.0/24 list=Connection-Vimeo
