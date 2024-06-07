<!-- Ajoutez ce code où vous souhaitez afficher la boîte de dialogue -->
<div id="sendEmailsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sélectionner les électeurs à contacter</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="emailSelectionForm">
                    <div class="checkbox">
                        <label><input type="checkbox" id="selectAll"> Tout sélectionner</label>
                    </div>
                    <div id="votersList">
                        <!-- La liste des électeurs sera chargée ici via AJAX -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary" id="sendEmailsButton">Envoyer e-mails</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Gérer le clic sur le bouton "Envoyer e-mails"
    $('#sendEmailsButton').click(function() {
        // Afficher la boîte de dialogue modale pour sélectionner les électeurs
        $('#sendEmailsModal').modal('show');
        
        // Charger la liste des électeurs via AJAX
        $.ajax({
            url: 'get_voters_list.php', // Changez le nom de fichier et l'URL en fonction de votre implémentation
            type: 'GET',
            success: function(response) {
                $('#votersList').html(response);
            }
        });
    });

    // Gérer le clic sur "Tout sélectionner"
    $('#selectAll').click(function() {
        $('input[name="selected_voters[]"]').prop('checked', $(this).prop('checked'));
    });
});
</script>
