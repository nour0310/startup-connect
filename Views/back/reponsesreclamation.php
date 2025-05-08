<?php
/**
 * Vue pour l'affichage détaillé d'une réclamation
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réclamation #<?= $data['reclamation']['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Carte de la réclamation -->
        <div class="card mb-4">
            <div class="card-header">
                <h2>Réclamation #<?= $data['reclamation']['id'] ?></h2>
            </div>
            <div class="card-body">
                <p><strong>Sujet :</strong> <?= htmlspecialchars($data['reclamation']['sujet']) ?></p>
                <p><strong>Description :</strong></p>
                <div class="bg-light p-3">
                    <?= nl2br(htmlspecialchars($data['reclamation']['description'])) ?>
                </div>
            </div>
        </div>

        <!-- Formulaire de réponse -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Ajouter une réponse</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <textarea class="form-control" name="response_text" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>
        </div>

        <!-- Liste des réponses -->
        <div class="card">
            <div class="card-header">
                <h3>Réponses (<?= count($data['reponses']) ?>)</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($data['reponses'])): ?>
                    <?php foreach ($data['reponses'] as $response): ?>
                        <div class="mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between">
                                <strong><?= htmlspecialchars($response['admin']) ?></strong>
                                <small class="text-muted"><?= $response['date'] ?></small>
                            </div>
                            <p class="mt-2"><?= nl2br(htmlspecialchars($response['text'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Aucune réponse pour le moment</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>