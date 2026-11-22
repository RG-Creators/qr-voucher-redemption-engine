</main>
</div>

<footer class="dashboard-footer">
    <div class="footer-container">

        <div class="footer-left">
            © <?php echo date('Y'); ?> QR Redemption System. All rights reserved.
        </div>

        <div class="footer-right">
            Created by 
            <a href="https://rgcreators.netlify.app/" target="_blank">
                RG
            </a>
        </div>

    </div>
</footer>

<style>
    html, body{
    height:100%;
}

body{
    display:flex;
    flex-direction:column;
    min-height:100vh;
}

/* Make main content expand */
main{
    flex:1;
}

/* Footer */
.dashboard-footer{
    background:#ffffff;
    border-top:1px solid #e5e7eb;
    padding:18px 25px;
    box-shadow:0 -5px 20px rgba(0,0,0,0.03);
}

.footer-container{
    max-width:1200px;
    margin:auto;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:10px;
    font-family:'Poppins',sans-serif;
    font-size:14px;
    color:#000;
}

.footer-right a{
    font-weight:600;
    text-decoration:none;
    color:#4f46e5;
    transition:0.2s;
}

.footer-right a:hover{
    color:#4338ca;
}

@media(max-width:768px){
    .footer-container{
        flex-direction:column;
        text-align:center;
    }
}
</style>

</body>
</html>