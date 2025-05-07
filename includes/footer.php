        </main> <!-- Fermeture du main ouvert dans header.php -->
    
    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Navigation</h3>
                <ul>
                    <li><a href="<?= BASE_URL ?>pages/markets.php">Marchés</a></li>
                    <li><a href="<?= BASE_URL ?>pages/faq.php">FAQ</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Légal</h3>
                <ul>
                    <li><a href="<?= BASE_URL ?>legal/terms.php">CGU</a></li>
                    <li><a href="<?= BASE_URL ?>legal/privacy.php">Confidentialité</a></li>
                </ul>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; <?= date('Y') ?> Crypto App. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
    <?php if(isset($page_js)): ?>
        <script src="<?= BASE_URL ?>assets/js/<?= $page_js ?>.js"></script>
    <?php endif; ?>
</body>
</html>
