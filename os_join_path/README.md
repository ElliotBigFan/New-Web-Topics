# 🔒 Lab: Path Traversal via os.path.join

## 📋 Mô tả
Lab demo lỗ hổng path traversal thông qua hành vi bất thường của `os.path.join` trong Python.

```python
>>> os.path.join('/var', 'log', '/etc/passwd')
'/etc/passwd'  # Bỏ qua tất cả path trước đó!
```

## 🚀 Chạy lab

### Docker (Khuyến nghị)
```bash
docker-compose up --build
```

### Trực tiếp
```bash
pip install Flask
python src/app.py
```

Truy cập: http://localhost:5000

## 🎯 Khai thác

1. **Đăng ký** với username: `/flag.txt`
2. **Đăng nhập** và tạo note
3. **Xem note** → đọc được file `/flag.txt`

## 🔍 Lỗ hổng

```python
# Hàm kiểm tra chỉ chặn ../
def is_safe_username(username):
    return '../' not in username and '..\\' not in username

# Nhưng os.path.join sẽ bỏ qua path trước đó nếu có absolute path
note_path = os.path.join('user/note', f"{username}.txt")
# Với username = "/flag.txt" → note_path = "/flag.txt"
```

## 🏁 Flag
`CTF{path_traversal_via_os_path_join}` 