<?php
/**
 * Plugin Name: CSV Download Button
 * Description: Adds a button that downloads user data as a CSV file
 * Version: 1.0
 * Author: Tom Frederik Leimbrock
 */

defined( 'ABSPATH' ) or die("This script cannot be run because wordpress is not loaded properly. ");

function generate_csv() {
    if (isset($_GET['download_csv']) && is_user_logged_in()) {
        $current_user = wp_get_current_user();

        $user_data = [
            ["Vorname", "Nachname", "E-Mail-Adresse"],
            [
                $current_user->user_firstname,
                $current_user->user_lastname,
                $current_user->user_email
            ]
        ];

        header('Content-type: text/csv');
        header("Content-Disposition: attachment; filename=user_data.csv");

        $output = fopen("php://output", "w");

        foreach ($user_data as $row) {
            fputcsv($output, $row, ";");
        }

        fclose($output);
        exit();
    }
}

add_action("init", "generate_csv");

function csv_download_button_shortcode() {
    if (is_user_logged_in()) {
        return '<a href="' . esc_url(add_query_arg('download_csv', 'true')) . '" class="button button-primary button-large">CSV-Export der Daten</a>';
    } else {
        return '<p>Bitte melde dich an, um deine persönlichen Daten herunterzuladen. </p>\m';
    }
}
add_shortcode('csv_download_button', 'csv_download_button_shortcode');
?>