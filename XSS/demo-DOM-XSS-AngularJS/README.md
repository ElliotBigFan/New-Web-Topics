# DOM XSS in AngularJS expression

- Khi trang có `<html ng-app>`, AngularJS sẽ scan DOM để tìm và đánh giá các expression dạng {{ ... }}. Nếu ứng dụng chèn dữ liệu do người dùng kiểm soát (query string, input) trực tiếp vào innerHTML, attacker có thể đưa {{...}} vào đó — Angular sẽ đánh giá biểu thức và có thể thực thi JavaScript trên trình duyệt nạn nhân → DOM XSS.