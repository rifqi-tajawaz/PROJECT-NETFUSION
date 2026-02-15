/ip firewall raw
rem [find address-list=Connection-Skype]
add action=add-dst-to-address-list address-list=Connection-Skype address-list-timeout=1d chain=prerouting content=skype.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION SKYPE  => {VIDEO CONPERENCE}"
add action=add-dst-to-address-list address-list=Connection-Skype address-list-timeout=1d chain=prerouting content=.skype. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Skype"]
add address=20.70.246.20 list=Connection-Skype
