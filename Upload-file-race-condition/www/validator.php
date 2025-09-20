<?php
function validate_and_maybe_delete($path) {
    // Giả lập thời gian quét/AV — tăng lên để dễ thử nghiệm race
    //sleep(5); // <-- Tăng cửa sổ race (thử thay bằng 10 nếu muốn)
    // sleep(2); // K cần sleep vẫn khai thác sử dựng group tab và send parallel 
    $allowed = ['gif', 'jpeg', 'jpg', 'png'];
    $ext = @end(explode('.', $path));
    if (!@in_array($ext, $allowed)) {
        // không phải ảnh: xóa file
        @unlink($path);
        echo "Validator: file deleted (not an image).";
    } else {
        echo "Validator: file ok (image).";
    }
}
