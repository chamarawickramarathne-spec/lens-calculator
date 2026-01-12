-- Sample data for Lens Calculator
-- Insert default categories

INSERT INTO category_types (name, description, display_order) VALUES
('Camera Body', 'Digital camera bodies and housings', 1),
('Lenses', 'Camera lenses and optical equipment', 2),
('Lighting', 'Studio and portable lighting equipment', 3),
('Audio', 'Microphones and audio recording equipment', 4),
('Stabilization', 'Tripods, gimbals, and stabilization gear', 5),
('Accessories', 'Memory cards, batteries, and other accessories', 6),
('Backdrops', 'Backgrounds and backdrop systems', 7),
('Props', 'Photography props and styling items', 8);

-- Insert equipment types
INSERT INTO equipment_types (type) VALUES
('Full Frame DSLR'),
('Mirrorless'),
('APS-C'),
('Medium Format'),
('Prime Lens'),
('Zoom Lens'),
('Telephoto Lens'),
('Wide Angle Lens'),
('Macro Lens'),
('Studio Light'),
('LED Panel'),
('Speedlight'),
('Continuous Light'),
('Lavalier Mic'),
('Shotgun Mic'),
('Wireless Mic'),
('Tripod'),
('Monopod'),
('Gimbal'),
('Slider'),
('Memory Card'),
('Battery'),
('Battery Grip'),
('Camera Bag'),
('Muslin Backdrop'),
('Paper Backdrop'),
('Collapsible Backdrop'),
('Reflector'),
('Diffuser');

-- Insert sample equipment for each category

-- Camera Bodies (type references: 1=Full Frame DSLR, 2=Mirrorless, 3=APS-C, 4=Medium Format)
INSERT INTO equipment_details (category_id, type_id, model, name, value, description) VALUES
(1, 1, 'Canon EOS 5D Mark IV', 'Canon 5D Mark IV Body', 50.00, 'Professional full-frame DSLR camera'),
(1, 1, 'Nikon D850', 'Nikon D850 Body', 55.00, 'High-resolution full-frame DSLR'),
(1, 2, 'Sony A7 III', 'Sony A7 III Body', 45.00, 'Full-frame mirrorless camera'),
(1, 2, 'Canon EOS R5', 'Canon R5 Body', 65.00, 'Advanced full-frame mirrorless'),
(1, 3, 'Canon EOS 90D', 'Canon 90D Body', 35.00, 'APS-C DSLR camera'),
(1, 4, 'Fujifilm GFX 50S', 'Fujifilm GFX 50S', 80.00, 'Medium format mirrorless');

-- Lenses (type: 5=Prime, 6=Zoom, 7=Telephoto, 8=Wide Angle, 9=Macro)
INSERT INTO equipment_details (category_id, type_id, model, name, value, description) VALUES
(2, 5, '50mm f/1.8', 'Standard 50mm Prime Lens', 15.00, 'Fast standard prime lens'),
(2, 5, '85mm f/1.4', 'Portrait 85mm Prime Lens', 30.00, 'Professional portrait lens'),
(2, 5, '35mm f/1.4', 'Wide 35mm Prime Lens', 25.00, 'Wide-angle prime lens'),
(2, 6, '24-70mm f/2.8', 'Standard Zoom 24-70mm', 35.00, 'Professional standard zoom'),
(2, 6, '70-200mm f/2.8', 'Telephoto Zoom 70-200mm', 40.00, 'Professional telephoto zoom'),
(2, 6, '16-35mm f/2.8', 'Wide Zoom 16-35mm', 35.00, 'Wide-angle zoom lens'),
(2, 9, '100mm f/2.8 Macro', 'Macro 100mm Lens', 28.00, 'Macro photography lens'),
(2, 7, '300mm f/4', 'Super Telephoto 300mm', 45.00, 'Long telephoto lens');

-- Lighting Equipment (type: 10=Studio Light, 11=LED Panel, 12=Speedlight, 13=Continuous Light)
INSERT INTO equipment_details (category_id, type_id, model, name, value, description) VALUES
(3, 10, 'Godox AD600', 'Studio Strobe 600W', 40.00, 'Professional studio strobe'),
(3, 10, 'Profoto B10', 'Portable Strobe B10', 50.00, 'High-end portable strobe'),
(3, 13, 'LED Panel 1000W', 'LED Panel Light', 30.00, 'Continuous LED lighting'),
(3, 12, 'Canon 600EX-RT', 'Speedlight Flash', 20.00, 'On-camera speedlight'),
(3, 11, 'Softbox 36x48', 'Large Softbox', 15.00, 'Large rectangular softbox'),
(3, 11, 'Octabox 47"', 'Octagonal Softbox', 18.00, 'Octagonal softbox'),
(3, 28, 'Beauty Dish 22"', 'Beauty Dish', 12.00, 'Portrait beauty dish'),
(3, 28, 'Five-in-One Reflector', '5-in-1 Reflector 43"', 8.00, 'Collapsible reflector set');

-- Audio Equipment (type: 14=Lavalier, 15=Shotgun, 16=Wireless)
INSERT INTO equipment_details (category_id, type_id, model, name, value, description) VALUES
(4, 15, 'Rode VideoMic Pro', 'Shotgun Microphone', 15.00, 'On-camera shotgun mic'),
(4, 14, 'Rode Wireless GO II', 'Wireless Lavalier System', 20.00, 'Wireless lav microphone'),
(4, 16, 'Audio-Technica AT4053b', 'Studio Hypercardioid Mic', 25.00, 'Professional studio mic'),
(4, 16, 'Zoom H6', 'Multi-track Audio Recorder', 18.00, 'Portable audio recorder');

-- Stabilization Equipment (type: 17=Tripod, 18=Monopod, 19=Gimbal, 20=Slider)
INSERT INTO equipment_details (category_id, type_id, model, name, value, description) VALUES
(5, 17, 'Manfrotto 055', 'Professional Tripod', 15.00, 'Heavy-duty tripod'),
(5, 17, 'Gitzo GT3543LS', 'Carbon Fiber Tripod', 25.00, 'Premium carbon fiber tripod'),
(5, 18, 'Manfrotto XPRO', 'Professional Monopod', 10.00, 'Aluminum monopod'),
(5, 19, 'DJI Ronin-S', 'Camera Gimbal Stabilizer', 35.00, '3-axis camera gimbal'),
(5, 20, 'Rhino 42" Slider', 'Camera Slider 42"', 28.00, 'Motorized camera slider');

-- Accessories (type: 21=Memory Card, 22=Battery, 23=Battery Grip, 24=Camera Bag)
INSERT INTO equipment_details (category_id, type_id, model, name, value, description) VALUES
(6, 21, 'SanDisk 128GB CFexpress', 'CFexpress Card 128GB', 5.00, 'High-speed memory card'),
(6, 21, 'SanDisk 64GB SD', 'SD Card 64GB', 3.00, 'SD memory card'),
(6, 22, 'Extra Camera Battery', 'Spare Battery Pack', 5.00, 'Additional camera battery'),
(6, 28, 'Polarizing Filter', 'CPL Filter 77mm', 8.00, 'Circular polarizer filter'),
(6, 28, 'ND Filter', 'Variable ND Filter', 10.00, 'Variable neutral density filter'),
(6, 28, 'Wireless Remote', 'Camera Remote Trigger', 5.00, 'Wireless camera trigger');

-- Backdrops (type: 25=Muslin, 26=Paper, 27=Collapsible)
INSERT INTO equipment_details (category_id, type_id, model, name, value, description) VALUES
(7, 26, 'Seamless Paper White', 'White Paper Backdrop 9ft', 12.00, 'White seamless paper roll'),
(7, 26, 'Seamless Paper Black', 'Black Paper Backdrop 9ft', 12.00, 'Black seamless paper roll'),
(7, 26, 'Seamless Paper Gray', 'Gray Paper Backdrop 9ft', 12.00, 'Gray seamless paper roll'),
(7, 25, 'Muslin Backdrop', 'Muslin Backdrop 10x12ft', 15.00, 'Collapsible muslin backdrop'),
(7, 28, 'Backdrop Support System', 'Backdrop Stand Kit', 10.00, 'Adjustable backdrop stand');

-- Props (using type 28=Reflector as generic type for props)
INSERT INTO equipment_details (category_id, type_id, model, name, value, description) VALUES
(8, 28, 'Studio Chair', 'Posing Chair', 8.00, 'Professional posing chair'),
(8, 28, 'Wooden Stool', 'Rustic Wooden Stool', 6.00, 'Vintage wooden stool'),
(8, 28, 'Vintage Props Set', 'Vintage Props Collection', 10.00, 'Assorted vintage props'),
(8, 28, 'Floral Arrangement', 'Artificial Flowers Set', 7.00, 'Decorative flower set');

-- Insert sample templates

-- Birthday Shoot Basic Template
INSERT INTO templates (name, description, labor_hours, hourly_rate, margin_percentage) VALUES
('Birthday Shoot - Basic', 'Basic birthday photography package with essential equipment', 3.00, 50.00, 25.00);

SET @birthday_basic_id = LAST_INSERT_ID();

INSERT INTO template_equipment (template_id, equipment_id, quantity) VALUES
(@birthday_basic_id, (SELECT id FROM equipment_details WHERE name = 'Canon 5D Mark IV Body' LIMIT 1), 1),
(@birthday_basic_id, (SELECT id FROM equipment_details WHERE name = 'Standard Zoom 24-70mm' LIMIT 1), 1),
(@birthday_basic_id, (SELECT id FROM equipment_details WHERE name = 'Portrait 85mm Prime Lens' LIMIT 1), 1),
(@birthday_basic_id, (SELECT id FROM equipment_details WHERE name = 'Speedlight Flash' LIMIT 1), 1),
(@birthday_basic_id, (SELECT id FROM equipment_details WHERE name = '5-in-1 Reflector 43"' LIMIT 1), 1),
(@birthday_basic_id, (SELECT id FROM equipment_details WHERE name = 'Professional Tripod' LIMIT 1), 1),
(@birthday_basic_id, (SELECT id FROM equipment_details WHERE name = 'SD Card 64GB' LIMIT 1), 2);

-- Wedding Shoot Professional Template
INSERT INTO templates (name, description, labor_hours, hourly_rate, margin_percentage) VALUES
('Wedding Shoot - Professional', 'Comprehensive wedding photography package with premium equipment', 8.00, 75.00, 30.00);

SET @wedding_pro_id = LAST_INSERT_ID();

INSERT INTO template_equipment (template_id, equipment_id, quantity) VALUES
(@wedding_pro_id, (SELECT id FROM equipment_details WHERE name = 'Canon R5 Body' LIMIT 1), 2),
(@wedding_pro_id, (SELECT id FROM equipment_details WHERE name = 'Standard Zoom 24-70mm' LIMIT 1), 1),
(@wedding_pro_id, (SELECT id FROM equipment_details WHERE name = 'Telephoto Zoom 70-200mm' LIMIT 1), 1),
(@wedding_pro_id, (SELECT id FROM equipment_details WHERE name = 'Portrait 85mm Prime Lens' LIMIT 1), 1),
(@wedding_pro_id, (SELECT id FROM equipment_details WHERE name = 'Wide Zoom 16-35mm' LIMIT 1), 1),
(@wedding_pro_id, (SELECT id FROM equipment_details WHERE name = 'Speedlight Flash' LIMIT 1), 2),
(@wedding_pro_id, (SELECT id FROM equipment_details WHERE name = 'Professional Tripod' LIMIT 1), 1),
(@wedding_pro_id, (SELECT id FROM equipment_details WHERE name = 'Camera Gimbal Stabilizer' LIMIT 1), 1),
(@wedding_pro_id, (SELECT id FROM equipment_details WHERE name = 'Spare Battery Pack' LIMIT 1), 4),
(@wedding_pro_id, (SELECT id FROM equipment_details WHERE name = 'CFexpress Card 128GB' LIMIT 1), 4);

-- Portrait Studio Session Template
INSERT INTO templates (name, description, labor_hours, hourly_rate, margin_percentage) VALUES
('Portrait Studio - Standard', 'Studio portrait session with professional lighting', 2.00, 60.00, 25.00);

SET @portrait_studio_id = LAST_INSERT_ID();

INSERT INTO template_equipment (template_id, equipment_id, quantity) VALUES
(@portrait_studio_id, (SELECT id FROM equipment_details WHERE name = 'Sony A7 III Body' LIMIT 1), 1),
(@portrait_studio_id, (SELECT id FROM equipment_details WHERE name = 'Portrait 85mm Prime Lens' LIMIT 1), 1),
(@portrait_studio_id, (SELECT id FROM equipment_details WHERE name = 'Standard 50mm Prime Lens' LIMIT 1), 1),
(@portrait_studio_id, (SELECT id FROM equipment_details WHERE name = 'Studio Strobe 600W' LIMIT 1), 2),
(@portrait_studio_id, (SELECT id FROM equipment_details WHERE name = 'Large Softbox' LIMIT 1), 1),
(@portrait_studio_id, (SELECT id FROM equipment_details WHERE name = 'Beauty Dish' LIMIT 1), 1),
(@portrait_studio_id, (SELECT id FROM equipment_details WHERE name = '5-in-1 Reflector 43"' LIMIT 1), 1),
(@portrait_studio_id, (SELECT id FROM equipment_details WHERE name = 'White Paper Backdrop 9ft' LIMIT 1), 1),
(@portrait_studio_id, (SELECT id FROM equipment_details WHERE name = 'Gray Paper Backdrop 9ft' LIMIT 1), 1),
(@portrait_studio_id, (SELECT id FROM equipment_details WHERE name = 'Backdrop Stand Kit' LIMIT 1), 1),
(@portrait_studio_id, (SELECT id FROM equipment_details WHERE name = 'Posing Chair' LIMIT 1), 1);

-- Product Photography Template
INSERT INTO templates (name, description, labor_hours, hourly_rate, margin_percentage) VALUES
('Product Photography - Basic', 'Basic product photography setup for e-commerce', 2.50, 55.00, 25.00);

SET @product_basic_id = LAST_INSERT_ID();

INSERT INTO template_equipment (template_id, equipment_id, quantity) VALUES
(@product_basic_id, (SELECT id FROM equipment_details WHERE name = 'Canon 90D Body' LIMIT 1), 1),
(@product_basic_id, (SELECT id FROM equipment_details WHERE name = 'Macro 100mm Lens' LIMIT 1), 1),
(@product_basic_id, (SELECT id FROM equipment_details WHERE name = 'LED Panel Light' LIMIT 1), 2),
(@product_basic_id, (SELECT id FROM equipment_details WHERE name = 'Large Softbox' LIMIT 1), 2),
(@product_basic_id, (SELECT id FROM equipment_details WHERE name = '5-in-1 Reflector 43"' LIMIT 1), 1),
(@product_basic_id, (SELECT id FROM equipment_details WHERE name = 'Professional Tripod' LIMIT 1), 1),
(@product_basic_id, (SELECT id FROM equipment_details WHERE name = 'White Paper Backdrop 9ft' LIMIT 1), 1),
(@product_basic_id, (SELECT id FROM equipment_details WHERE name = 'Wireless Remote Trigger' LIMIT 1), 1);

-- Event Photography Template
INSERT INTO templates (name, description, labor_hours, hourly_rate, margin_percentage) VALUES
('Event Coverage - Standard', 'Standard event photography coverage', 4.00, 65.00, 25.00);

SET @event_standard_id = LAST_INSERT_ID();

INSERT INTO template_equipment (template_id, equipment_id, quantity) VALUES
(@event_standard_id, (SELECT id FROM equipment_details WHERE name = 'Canon 5D Mark IV Body' LIMIT 1), 1),
(@event_standard_id, (SELECT id FROM equipment_details WHERE name = 'Standard Zoom 24-70mm' LIMIT 1), 1),
(@event_standard_id, (SELECT id FROM equipment_details WHERE name = 'Telephoto Zoom 70-200mm' LIMIT 1), 1),
(@event_standard_id, (SELECT id FROM equipment_details WHERE name = 'Wide Zoom 16-35mm' LIMIT 1), 1),
(@event_standard_id, (SELECT id FROM equipment_details WHERE name = 'Speedlight Flash' LIMIT 1), 2),
(@event_standard_id, (SELECT id FROM equipment_details WHERE name = 'Professional Monopod' LIMIT 1), 1),
(@event_standard_id, (SELECT id FROM equipment_details WHERE name = 'Spare Battery Pack' LIMIT 1), 2),
(@event_standard_id, (SELECT id FROM equipment_details WHERE name = 'SD Card 64GB' LIMIT 1), 3);
