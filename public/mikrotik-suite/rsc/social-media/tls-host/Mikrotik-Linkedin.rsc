/ip firewall raw
rem [find address-list=Connection-Linkedin]
add action=add-dst-to-address-list address-list=Connection-Linkedin address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*linkedin.com* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION LINKEDIN => {SOCIAL MEDIA}"
add action=add-dst-to-address-list address-list=Connection-Linkedin address-list-timeout=1d chain=prerouting protocol=tcp tls-host=*.linkedin.* dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Linkedin"]
add address=103.20.92.0/24 list=Connection-Linkedin
add address=103.20.93.0/24 list=Connection-Linkedin
add address=103.20.94.0/24 list=Connection-Linkedin
add address=103.20.95.0/24 list=Connection-Linkedin
add address=108.174.0.0/24 list=Connection-Linkedin
add address=108.174.1.0/24 list=Connection-Linkedin
