<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home - Kost Elisa</title>
    <!-- Link CSS dan Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style2.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <!-- Kontainer Atas -->
    <div class="top-container">
        <!-- Status (konten utama) -->
        <div class="status">
            <div class="header">
                <h4 id="big">Data Analisis</h4>
                <h4 id="small">Aktivitas Mingguan</h4>
            </div>

            <div class="items-list">
                <div class="item">
                    <div class="info">
                        <div>
                            <h5>Data Analysis</h5>
                            <p>- 3 lessons left</p>
                            <p>- 1 project left</p>
                        </div>
                        <i class='bx bx-data'></i>
                    </div>
                    <div class="progress">
                        <div class="bar"></div>
                    </div>
                </div>
                <div class="item">
                    <div class="info">
                        <div>
                            <h5>Machine Learn</h5>
                            <p>- 2 assignments left</p>
                            <p>- 5 tutorials left</p>
                        </div>
                        <i class='bx bx-terminal'></i>
                    </div>
                    <div class="progress">
                        <div class="bar"></div>
                    </div>
                </div>
                <div class="item">
                    <div class="info">
                        <div>
                            <h5>Python</h5>
                            <p>- 4 chapters left</p>
                            <p>- 8 quizzes left</p>
                        </div>
                        <i class='bx bxl-python'></i>
                    </div>
                    <div class="progress">
                        <div class="bar"></div>
                    </div>
                </div>
                <div class="item">
                    <canvas class="activity-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Kontainer Bawah -->
    <div class="bottom-container">
        <!-- Status Belajar -->
        <div class="prog-status">
            <div class="header">
                <h4>Learning Progress</h4>
                <div class="tabs">
                    <a href="#" class="active">1Y</a>
                    <a href="#">6M</a>
                    <a href="#">3M</a>
                </div>
            </div>

            <div class="details">
                <div class="item">
                    <h2>3.45</h2>
                    <p>Current GPA</p>
                </div>
                <div class="separator"></div>
                <div class="item">
                    <h2>4.78</h2>
                    <p>Class Average GPA</p>
                </div>
            </div>

            <canvas class="prog-chart"></canvas>
        </div>

        <!-- Konten Populer -->
        <div class="popular">
            <div class="header">
                <h4>Popular</h4>
                <a href="#"># Data</a>
            </div>

            <img src="assets/podcast.jpg" alt="Podcast">
            <div class="audio">
                <i class='bx bx-podcast'></i>
                <a href="#">Podcast: Mastering Data Visualization</a>
            </div>
            <p>Learn to create compelling visualizations with data.</p>
            <div class="listen">
                <div class="author">
                    <img src="assets/profile.png" alt="Profile">
                    <div>
                        <a href="#">Alex</a>
                        <p>Data Analyst</p>
                    </div>
                </div>
                <button>Listen <i class='bx bx-right-arrow-alt'></i></button>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="upcoming">
            <div class="header">
                <h4>You may like it</h4>
                <a href="#">July <i class='bx bx-chevron-down'></i></a>
            </div>

            <div class="dates">
                <div class="item">
                    <h5>Mo</h5>
                    <a href="#">12</a>
                </div>
                <div class="item active">
                    <h5>Tu</h5>
                    <a href="#">13</a>
                </div>
                <!-- Tambahan tanggal lainnya -->
            </div>

            <div class="events">
                <div class="item">
                    <div>
                        <i class='bx bx-time'></i>
                        <div class="event-info">
                            <a href="#">Data Science</a>
                            <p>10:00-11:30</p>
                        </div>
                    </div>
                    <i class='bx bx-dots-horizontal-rounded'></i>
                </div>
                <!-- Tambahan event lainnya -->
            </div>
        </div>
    </div>

    <!-- Skrip JS -->
    <script src="script2.js"></script>
</body>
</html>
