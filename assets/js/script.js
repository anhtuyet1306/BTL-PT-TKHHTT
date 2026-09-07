/**
 * HỆ THỐNG QUẢN LÝ TUYỂN DỤNG - JAVASCRIPT
 */
document.addEventListener('DOMContentLoaded', function () {
    // 1. Tự động tính điểm trung bình trên trang danh-gia.php
    const scoreInputs = document.querySelectorAll('.score-input');
    const bigScoreElem = document.querySelector('.big-score');

    if (scoreInputs.length > 0 && bigScoreElem) {
        scoreInputs.forEach(input => {
            input.addEventListener('input', updateAverageScore);
        });

        function updateAverageScore() {
            let total = 0;
            let count = 0;
            scoreInputs.forEach(inp => {
                const val = parseFloat(inp.value) || 0;
                total += val;
                count++;
            });

            if (count > 0) {
                const avg = (total / count).toFixed(1);
                bigScoreElem.innerHTML = avg + ' <small>/ 10</small>';
            }
        }
    }
});
