from flask import Flask, request, render_template, redirect, url_for, session, flash
import os

app = Flask(__name__)
app.secret_key = 'secret123'

# Tạo file flag
with open('flag.txt', 'w') as f:
    f.write('CTF{path_traversal_via_os_path_join}')

# Tạo thư mục notes
os.makedirs('user/note', exist_ok=True)

# Database đơn giản
users = {}

def is_safe_username(username):
    """Chỉ chặn ../ nhưng không biết về absolute path"""
    return '../' not in username and '..\\' not in username

@app.route('/')
def index():
    if 'username' in session:
        return redirect('/dashboard')
    return render_template('index.html')

@app.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        
        if not is_safe_username(username):
            return render_template('error.html',
                error_title='Lỗi',
                error_icon='❌',
                error_message='Username không hợp lệ! Không được chứa ../',
                alert_type='danger',
                links=[
                    {'url': '/register', 'text': 'Thử lại', 'class': ''},
                    {'url': '/', 'text': 'Về trang chủ', 'class': 'btn-secondary'}
                ]
            )
        
        if username in users:
            return render_template('error.html',
                error_title='Lỗi',
                error_icon='❌',
                error_message='Username đã tồn tại!',
                alert_type='danger',
                links=[
                    {'url': '/register', 'text': 'Thử lại', 'class': ''},
                    {'url': '/', 'text': 'Về trang chủ', 'class': 'btn-secondary'}
                ]
            )
        
        users[username] = password
        return render_template('error.html',
            error_title='Thành công',
            error_icon='✅',
            error_message='Đăng ký thành công!',
            alert_type='success',
            links=[
                {'url': '/login', 'text': '🔐 Đăng nhập', 'class': ''},
                {'url': '/', 'text': 'Về trang chủ', 'class': 'btn-secondary'}
            ]
        )
    
    return render_template('register.html')

@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        
        if username in users and users[username] == password:
            session['username'] = username
            return redirect('/dashboard')
        else:
            return render_template('error.html',
                error_title='Lỗi',
                error_icon='❌',
                error_message='Sai username hoặc password!',
                alert_type='danger',
                links=[
                    {'url': '/login', 'text': 'Thử lại', 'class': ''},
                    {'url': '/', 'text': 'Về trang chủ', 'class': 'btn-secondary'}
                ]
            )
    
    return render_template('login.html')

@app.route('/dashboard')
def dashboard():
    if 'username' not in session:
        return redirect('/login')
    
    username = session['username']
    return render_template('dashboard.html', username=username)

@app.route('/save_note', methods=['POST'])
def save_note():
    if 'username' not in session:
        return redirect('/login')
    
    username = session['username']
    note_content = request.form['note']
    
    # LỖ HỔNG: os.path.join sẽ bỏ qua NOTES_DIR nếu username là absolute path
    note_filename = f"{username}.txt"
    note_path = os.path.join('user/note', note_filename)
    
    # Thêm xuống dòng mới thay vì ghi đè
    with open(note_path, 'a') as f:
        f.write(note_content + '\n')
    
    return redirect('/dashboard')

@app.route('/view_note')
def view_note():
    if 'username' not in session:
        return redirect('/login')
    
    username = session['username']
    
    # LỖ HỔNG: os.path.join sẽ bỏ qua NOTES_DIR nếu username là absolute path
    note_filename = f"{username}.txt"
    note_path = os.path.join('user/note', note_filename)
    
    try:
        with open(note_path, 'r') as f:
            content = f.read()
        return render_template('view_note.html', 
                             filename=note_filename, 
                             filepath=note_path, 
                             content=content)
    except:
        return render_template('error.html',
            error_title='Lỗi',
            error_icon='❌',
            error_message=f'Không thể đọc file: {note_path}',
            alert_type='danger',
            links=[
                {'url': '/dashboard', 'text': '📋 Về Dashboard', 'class': ''},
                {'url': '/logout', 'text': '🚪 Đăng xuất', 'class': 'btn-danger'}
            ]
        )

@app.route('/logout')
def logout():
    session.pop('username', None)
    return redirect('/')

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
