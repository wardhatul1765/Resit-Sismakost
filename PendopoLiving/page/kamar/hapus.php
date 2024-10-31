<?php
  $idKamar = $_GET['idKamar'];

  $koneksi->query("DELETE FROM kamar where idKamar='$idKamar'");
?>

<script type="text/javascript">
    window.location.href="?page=kamar";
</script>