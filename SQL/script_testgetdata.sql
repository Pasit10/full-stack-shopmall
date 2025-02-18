USE SHOPMALL;

SELECT * FROM customer;
SELECT * FROM cart;

SELECT * FROM stock;

SELECT * FROM transaction;
SELECT * FROM transactionlog;
SELECT * FROM transactiondetail;
CALL AddTransactionDetails(1);


INSERT INTO STOCK (ProductName, PricePerUnit, CostPerUnit, Detail, StockQtyFrontEnd, StockQtyBackEnd, ProductImagePath)
VALUES
('Laptop A15', 599.99, 450.00, 'High-performance laptop with 16GB RAM and 512GB SSD', 25, 25, '/images/laptop_a15.png'),
('Smartphone X5', 399.49, 300.00, '5G-enabled smartphone with 128GB storage and triple camera', 50, 50, '/images/smartphone_x5.jpg'),
('Wireless Headphones', 79.99, 50.00, 'Noise-cancelling over-ear headphones with Bluetooth', 100, 100, '/images/headphones.jpg'),
('Smartwatch Series 4', 199.99, 150.00, 'Fitness tracker and smartwatch with heart rate monitor', 30, 30, '/images/smartwatch_series4.png'),
('Gaming Keyboard', 49.99, 30.00, 'Mechanical RGB backlit gaming keyboard', 75, 75, '/images/gaming_keyboard.png'),
('4K TV 55-Inch', 799.00, 600.00, 'Ultra HD Smart TV with HDR support and streaming apps', 20, 20, '/images/4k_tv.jpg'),
('Bluetooth Speaker', 29.99, 20.00, 'Portable Bluetooth speaker with 10 hours of battery life', 150, 150, '/images/bluetooth_speaker.jpg'),
('Digital Camera Z10', 499.99, 400.00, 'Mirrorless camera with 24MP sensor and 4K video recording', 15, 15, '/images/digital_camera.png'),
('External Hard Drive', 89.49, 60.00, '2TB portable hard drive with USB 3.0', 60, 60, '/images/external_hard_drive.jpg'),
('Office Chair Deluxe', 129.99, 100.00, 'Ergonomic office chair with lumbar support and adjustable height', 40, 40, '/images/office_chair.jpg');

INSERT INTO transactionstatus VALUES
(1,"ยังไม่รับออเดอร์"),(2,"รับออเดอร์"),(3,"เริ่มแพคเกจ"),(4,"ส่งแพคเกจแล้ว"),(5,"ส่งให้ขนส่ง"),(6,"ยกเลิกออเดอร์")

INSERT INTO admin VALUES (1,"admin","admin","09215485");

-- ขก. หารูป

-- INSERT INTO STOCK (ProductName, PricePerUnit, Detail, StockQty, ProductImagePath)
-- VALUES
-- ('Wireless Mouse', 19.99, 'Ergonomic wireless mouse with adjustable DPI', 200, '/images/wireless_mouse.jpg'),
-- ('USB-C Charger', 15.49, 'Fast-charging USB-C adapter with 20W power', 300, '/images/usb_c_charger.jpg'),
-- ('Tablet Pro 10', 349.00, '10-inch tablet with 64GB storage and stylus support', 40, '/images/tablet_pro_10.jpg'),
-- ('Electric Kettle', 25.99, '1.7L electric kettle with auto shut-off feature', 100, '/images/electric_kettle.jpg'),
-- ('Gaming Mouse Pad', 12.99, 'Large gaming mouse pad with RGB lighting', 250, '/images/gaming_mouse_pad.jpg'),
-- ('Fitness Band Plus', 49.99, 'Waterproof fitness tracker with heart rate monitoring', 80, '/images/fitness_band.jpg'),
-- ('Noise-Cancelling Earbuds', 129.99, 'Wireless earbuds with active noise cancellation', 120, '/images/noise_cancelling_earbuds.jpg'),
-- ('Home Security Camera', 69.99, '1080p home security camera with night vision', 50, '/images/home_security_camera.jpg'),
-- ('Robot Vacuum Cleaner', 299.99, 'Smart robotic vacuum with app control', 30, '/images/robot_vacuum.jpg'),
-- ('Standing Desk', 399.99, 'Adjustable height standing desk with motorized controls', 15, '/images/standing_desk.jpg'),
-- ('Portable Power Bank', 34.99, '20,000mAh portable power bank with USB-C and USB-A ports', 200, '/images/power_bank.jpg'),
-- ('Action Camera 4K', 149.99, 'Waterproof 4K action camera with multiple mounts', 60, '/images/action_camera.jpg'),
-- ('LED Desk Lamp', 22.99, 'Adjustable LED desk lamp with touch control', 150, '/images/led_desk_lamp.jpg'),
-- ('Smart Light Bulb', 12.49, 'Wi-Fi enabled smart bulb with color changing feature', 400, '/images/smart_light_bulb.jpg'),
-- ('Bluetooth Keyboard', 39.99, 'Compact Bluetooth keyboard compatible with multiple devices', 75, '/images/bluetooth_keyboard.jpg'),
-- ('Instant Pot Cooker', 89.99, 'Multi-functional pressure cooker with 7 cooking modes', 50, '/images/instant_pot.jpg'),
-- ('Electric Toothbrush', 29.99, 'Rechargeable electric toothbrush with 5 cleaning modes', 100, '/images/electric_toothbrush.jpg'),
-- ('Smart Thermostat', 199.99, 'Energy-saving smart thermostat with app control', 25, '/images/smart_thermostat.jpg'),
-- ('Portable Air Purifier', 99.99, 'Compact air purifier with HEPA filter', 40, '/images/air_purifier.jpg'),
-- ('Gaming Console X', 499.00, 'Next-gen gaming console with 1TB storage', 20, '/images/gaming_console_x.jpg');

SELECT ID FROM COMPANY
WHERE EMPLOYEES > 10000 ORDER BY ID;