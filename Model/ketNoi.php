<?php
    class clsKetNoi {
        private $servername = "localhost";
        private $username = "cnmoi";
        private $password = "123456";
        private $dbname = "restaurantdb";

        public function moKetNoi() {
            $con = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);

            // Kiểm tra kết nối
            if (!$con) {
                die("Kết nối thất bại: " . mysqli_connect_error());
            }

            // Đặt charset UTF-8 để hỗ trợ tiếng Việt
            mysqli_set_charset($con, "utf8");

            return $con;
        }

        public function dongKetNoi($con) {
            if ($con) {
                mysqli_close($con);
            }
        }
    }
?>
