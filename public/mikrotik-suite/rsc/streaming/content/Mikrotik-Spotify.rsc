/ip firewall raw
rem [find address-list=Connection-Spotify]
add action=add-dst-to-address-list address-list=Connection-Spotify address-list-timeout=1d chain=prerouting content=spotify.com dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP comment="// CONNECTION SPOTIFY => {STREAMING}"
add action=add-dst-to-address-list address-list=Connection-Spotify address-list-timeout=1d chain=prerouting content=.spotify. dst-address-list=!LOCAL-IP src-address-list=LOCAL-IP

/ip firewall address-list
rem [find list="Connection-Spotify"]
add address=35.186.224.19 list=Connection-Spotify
add address=193.182.8.0/21 list=Connection-Spotify
