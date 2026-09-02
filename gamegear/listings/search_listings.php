<?php
$conn = mysqli_connect('localhost', 'root', '', 'gamegear_exchange');

if ($conn) {
    $search = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';
    
    $sql = "SELECT l.*, c.category_name 
            FROM listings l 
            JOIN categories c ON l.category_id = c.category_id 
            WHERE l.status = 'Available' AND l.title LIKE '%$search%' 
            ORDER BY l.created_at DESC";
            
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr class="product-item" data-category="'. strtolower(htmlspecialchars($row['category_name'])) .'">';
            
            echo '  <td style="text-align: center;"><img src="../images/' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['title']) . '" style="width: 100px; height: auto;"></td>';
            echo '  <td><strong>'. htmlspecialchars($row['title']) .'</strong></td>';
            echo '  <td style="text-align: center;">'. htmlspecialchars($row['category_name']) .'</td>';
            
            echo '  <td style="text-align: center;">'. htmlspecialchars($row['item_condition']) .'</td>';
            echo '  <td class="new-price" style="text-align: center; font-weight: bold; color: #5ea825;">RM '. number_format($row['price'], 2) .'</td>';
            echo '  <td style="text-align: center; white-space: nowrap;"><a href="details.php?id='. $row['listing_id'] .'" class="cta-button" style="padding: 8px 12px; font-size: 14px; text-decoration: none; background-color: #82e043; color: #111111; font-weight: bold; border-radius: 4px;">View Details</a></td>';
            
            echo '</tr>';
        }
    } else {
        echo '<tr>';
        echo '  <td colspan="6" style="text-align: center; padding: 40px;">';
        echo '      <p style="color: #666; font-size: 1.2em; margin-bottom: 20px;">No products found matching your search.</p>';
        echo '      <button onclick="document.getElementById(\'searchBox\').value=\'\'; searchListings();" class="submit-btn" style="width: auto; padding: 10px 25px; font-size: 16px;">Clear Search & View All</button>';
        echo '  </td>';
        echo '</tr>';
    }
    
    mysqli_close($conn);
}
?>