/ip firewall raw
rem [find address-list=Connection-Telegram]
add action=add-dst-to-address-list address-list=Connection-Telegram address-list-timeout=1d chain=prerouting content=telegram.org dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION TELEGRAM => {SOCIAL MEDIA}"
add action=add-dst-to-address-list address-list=Connection-Telegram address-list-timeout=1d chain=prerouting content=.telegram. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP
add action=add-dst-to-address-list address-list=Connection-Telegram address-list-timeout=1d chain=prerouting content=t.me dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Telegram"]
add address=149.154.172.0/22 list=Connection-Telegram
add address=149.154.168.0/22 list=Connection-Telegram
add address=149.154.164.0/22 list=Connection-Telegram
add address=91.108.56.0/22 list=Connection-Telegram
add address=91.108.4.0/22 list=Connection-Telegram
add address=149.154.160.0/20 list=Connection-Telegram
add address=91.108.4.0/22 list=Connection-Telegram
add address=91.108.8.0/22 list=Connection-Telegram
add address=91.108.12.0/22 list=Connection-Telegram
add address=91.108.16.0/22 list=Connection-Telegram
