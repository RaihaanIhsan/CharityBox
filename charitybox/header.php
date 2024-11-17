<style>
header {
    background-color: #534C3C;
    padding: 30px 65px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header-content {
    display: flex;
    align-items: center;
    width: 100%;  
}

.logo {
    width: 150px;
}

nav {
    margin-left: auto; /* Pushes the navigation to the right */
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
}

.nav-links a {
    color: #E9ECEB;
    text-decoration: none;
    font-size: 16px;
}

.nav-links a:hover {
    color: #ffcc00;
 }
</style>

<header>
        <div class="header-content">
            <img src="./images/logo.png" alt="CharityBox Logo" class="logo">
            <nav>
                <ul class="nav-links">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="about_us.php">About Us</a></li>
                    <li><a href="https://forms.gle/PqXqAfgjjC8Uszqv6">Volunteer</a></li>
                    <li><a href="organization_list.php">Donate</a></li>
                </ul>
            </nav>
        </div>
</header>
