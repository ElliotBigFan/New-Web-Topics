FROM python:3.9-slim

WORKDIR /app

COPY requirements.txt .
RUN pip install -r requirements.txt

COPY src/app.py .
COPY templates/ ./templates/

# Tạo file flag
RUN echo "CTF{path_traversal_via_os_path_join}" > /flag.txt

EXPOSE 5000

CMD ["python", "app.py"]