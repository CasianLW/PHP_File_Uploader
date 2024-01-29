<?php

class FileImageUploader {
    private $targetDirectory = "public/uploads/"; // Dossier de destination des uploads
    private $maxSize = 2; // Taille maximale du fichier en mo
    private $allowedTypes = ['jpg', 'png']; // Types de fichiers autorisés
    private $fileName; // Nom du fichier à uploader
    private $fileTmpName; // Nom temporaire du fichier
    private $fileType; // Type du fichier
    private $desiredName; // Nom désiré du fichier

    public function __construct($file, $desiredName = "") {
        $this->fileTmpName = $file['tmp_name'];
        $this->fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $this->desiredName = $desiredName; 
        $this->fileName = $desiredName ? $desiredName . '.' . $this->fileType : basename($file['name']);
        
    }

    // Vérifie si le fichier est une image
    public function isImage() {
        $check = getimagesize($this->fileTmpName);
        return $check !== false;
    }

        // Vérifie la taille du fichier
    public function isValidSize() {
        return filesize($this->fileTmpName) <= ($this->maxSize*1000000);
    }

    // Vérifie le type du fichier
    public function isValidType() {
        return in_array($this->fileType, $this->allowedTypes);
    }
    public function scanDirectory() {
        $files = scandir($this->targetDirectory);
        $files = array_diff($files, array('.', '..'));
        return $files;
    }
    

    // Uploade le fichier
    public function upload() {
        try {
            
            if ($this->fileTmpName == '' || $_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
                throw new Exception("Aucun fichier n'a été sélectionné.");
            }

            if (!$this->isImage()) {
                throw new Exception("Le fichier n'est pas une image.");
            }
    
            if (!$this->isValidSize()) {
                throw new Exception("La taille du fichier dépasse la limite autorisée de " . ($this->maxSize ) . " Mo.");

            }
    
            if (!$this->isValidType()) {
                throw new Exception("Le type de fichier n'est pas autorisé. Les types valides sont : " . implode(', ', $this->allowedTypes) . ".");
            }
    
            if (file_exists($this->targetDirectory . $this->fileName)) {
                if (empty($this->desiredName)) {
                    $baseName = pathinfo($this->fileName, PATHINFO_FILENAME);
                    $extension = $this->fileType;
                    $i = 1;
                    do {
                        $newFileName = $baseName . "-" . $i . "." . $extension;
                        $i++;
                    } while (file_exists($this->targetDirectory . $newFileName));
                    $this->fileName = $newFileName; 
                } else {
                    throw new Exception("Un fichier avec le même nom existe déjà.");
                }
            }
            if (!move_uploaded_file($this->fileTmpName, $this->targetDirectory . $this->fileName)) {
                throw new Exception("Erreur lors de l'upload du fichier.");
            }

            return ['success' => true, 'message' => "Le fichier a été uploadé avec succès (" . $this->fileName . ")."];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
}

?>
