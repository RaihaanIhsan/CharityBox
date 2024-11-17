<?php
function fetchOrganizationName($conn, $org_id) {
    $stmt = $conn->prepare("SELECT name FROM organizations WHERE id = ?");
    $stmt->bind_param("i", $org_id);
    $stmt->execute();
    $stmt->bind_result($org_name);
    $stmt->fetch();
    $stmt->close();
    return $org_name;
}

function handleFileUploads($files, $upload_dir, $allowed_types, $max_files) {
    $image_paths = [];
    $file_count = count($files['name']);
    if ($file_count > $max_files) {
        return [];
    }

    for ($i = 0; $i < $file_count; $i++) {
        $file_name = $files['name'][$i];
        $file_tmp = $files['tmp_name'][$i];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_types)) {
            continue;
        }

        $unique_name = uniqid() . '.' . $file_ext;
        $upload_path = $upload_dir . $unique_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $image_paths[] = $upload_path;
        }
    }
    return $image_paths;
}
