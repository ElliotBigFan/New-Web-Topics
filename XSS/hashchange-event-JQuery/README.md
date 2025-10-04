
# Port Swigger: [Lab] DOM XSS in jQuery selector sink using a hashchange event

---

## Vì sao `<img src=x onerror=alert(1)>` thường thành công còn `<script>…</script>` hay `<iframe src=x onload=alert(1)>` thường thất bại khi đưa HTML vào `$(...)` ?

---

## Tóm tắt ngắn

Khi đưa một chuỗi HTML vào `$(...)` hoặc `innerHTML` trong các lab DOM XSS, payload dạng

```html
<img src=x onerror=alert(1)>
```

thường thành công hơn so với

```html
<script>alert(1)</script>
```

hoặc

```html
<iframe src=x onload=alert(1)></iframe>
```

Lý do chính: **`<img>` với `onerror` là một thuộc tính sự kiện của element** — khi element được tạo và trình duyệt cố tải `src`, event `error` sẽ được kích hoạt và handler inline được chạy. Trong khi đó, `<script>` chứa mã cần *được thực thi* bởi engine JS (không chỉ tồn tại như thuộc tính), và `iframe` có lifecycle tải phức tạp nên không đảm bảo chạy ngay.

---

## Giải thích chi tiết (theo trường hợp)

### 1) Tại sao `<img src=x onerror=...>` chạy rất đáng tin cậy

- Khi browser tạo một thẻ `<img>` và chèn vào DOM, nó sẽ cố gắng tải tài nguyên chỉ định bởi `src` ngay lập tức.
- Nếu `src` không hợp lệ (ví dụ `x`), sự kiện `error` sẽ xảy ra và thuộc tính inline `onerror` (một hàm) được gọi.
- `onerror` là **thuộc tính của element** — không cần trình duyệt phải "đánh giá" mã từ một node `<script>` riêng biệt.

**Ví dụ:**
```js
var img = document.createElement('img');
img.src = 'x';
img.onerror = function(){ alert('img onerror fired'); };
document.body.appendChild(img);
```
Khi jQuery parse `'<img src=x onerror=alert(1)>'` nó tạo ra element tương tự — và event sẽ chạy khi lỗi tải xảy ra.


### 2) Tại sao `<script>alert(1)</script>` thường không chạy

- Khi chèn HTML bằng `innerHTML` hoặc khi jQuery parse một chuỗi HTML (ví dụ `$(htmlString)`), **nhiều trình duyệt không tự động thực thi** nội dung bên trong thẻ `<script>` mà chỉ tạo node DOM.
- jQuery có logic xử lý script trong một số phương thức (ví dụ `.append()`), nó có thể tách và đánh giá các script bằng cách tạo thẻ `<script>` mới rồi chèn — nhưng hành vi thay đổi theo phiên bản jQuery và theo phương thức được dùng.
- Kết quả: chèn chuỗi chứa `<script>` **không đảm bảo** mã trong `<script>` sẽ được thực thi.

- Thử minh họa:

```js
// không chắc chạy: innerHTML parsing
var container = document.createElement('div');
container.innerHTML = '<script>alert("hi")</script>';
document.body.appendChild(container); // alert có thể hoặc không chạy tuỳ browser/jQuery
```

**Hệ quả thực tế:** payload dạng `<script>...</script>` ít ổn định hơn, phụ thuộc vào cách parse/insert và phiên bản jQuery.


### 3) Tại sao `<iframe src=x onload=...>` thường thất bại hoặc không ổn định

- `iframe` có lifecycle phức tạp: `onload` chỉ chạy khi iframe thực sự tải tài nguyên ở `src`.
- `src="x"` có thể được hiểu là đường dẫn tương đối — trình duyệt có thể cố tải `./x` (trả 404) hoặc xử lý khác nhau giữa các browser; `onload` không đảm bảo chạy trong mọi tình huống.
- Khi iframe được tạo từ chuỗi HTML bằng `innerHTML`/jQuery, việc attach/trigger `onload` còn phụ thuộc vào thời điểm và cơ chế chèn; đôi khi thuộc tính inline không được thực thi như mong muốn.
- Ngoài ra còn có các yếu tố khác: **CSP**, **SOP (cross-origin)**, hoặc cơ chế của trang test có thể ngăn hoặc thay đổi hành vi tải iframe.

Tóm lại: `iframe onload` phức tạp và không đáng tin bằng `img onerror`.


## Điểm mấu chốt về cách browser và jQuery/DOM xử lý HTML chuỗi

- **Event attributes** (ví dụ `onerror`, `onclick`) là thuộc tính của element. Nếu element đó được tạo và browser nhìn thấy event phù hợp (ví dụ lỗi tải), handler sẽ chạy.
- **`<script>` tags** chứa mã cần *được thực thi* bởi JS engine — việc này **không** luôn xảy ra khi chèn bằng `innerHTML` hoặc `$()`; jQuery có code để xử lý scripts trong vài trường hợp nhưng không phải mọi khi.
- Hành vi phụ thuộc vào:
  - Phiên bản jQuery và API bạn dùng (`$(frag)` khác `.append(frag)`...),
  - Cách browser parse/insert (`innerHTML` vs `createElement`),
  - CSP / Same-origin / timing / server responses.

Vì vậy, attacker thường chọn payload ngắn và đáng tin nhất: **`<img src=x onerror=...>`**.


---

## Kết luận — tại sao lab khuyên dùng `<img onerror=print()>`

- `img onerror` đơn giản và reliable vì dựa trên event của element khi trình duyệt cố tải `src`.
- `script` cần được *thực thi* — chèn bằng chuỗi HTML không đảm bảo thực thi.
- `iframe onload` phụ thuộc nhiều yếu tố (load timing, đường dẫn, CSP, browser) nên ít ổn định.

Vì vậy trong nhiều lab DOM XSS, payload an toàn và đáng tin nhất để trigger là `\<img src=x onerror=...\>`.

---

## Ghi chú thêm

- Hành vi thực tế có thể thay đổi giữa các phiên bản jQuery và các browser. Luôn test trên môi trường mục tiêu.
- Nếu muốn payload `<script>` chạy, cách an toàn là tạo script element bằng `document.createElement('script')`, gán `text` hoặc `src` rồi append — cách này đảm bảo script được thực thi.

## Lab thực hành:
- https://portswigger.net/web-security/cross-site-scripting/dom-based/lab-jquery-selector-hash-change-event

*Hết.*