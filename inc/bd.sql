-- Create tables
CREATE TABLE emp_membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    date_naissance DATE,
    genre ENUM('M', 'F'),
    email VARCHAR(100),
    ville VARCHAR(100),
    mdp VARCHAR(255),
    image_profil VARCHAR(255)
);

CREATE TABLE emp_categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100)
);

CREATE TABLE emp_objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100),
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES emp_categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES emp_membre(id_membre)
);

CREATE TABLE emp_image (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255),
    FOREIGN KEY (id_objet) REFERENCES emp_objet(id_objet)
);

CREATE TABLE emp_emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES emp_objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES emp_membre(id_membre)
);

-- Insert test data
-- Insert members
INSERT INTO emp_membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice', '1990-05-15', 'F', 'alice@example.com', 'Paris', 'password1', 'alice.jpg'),
('Bob', '1985-03-22', 'M', 'bob@example.com', 'Lyon', 'password2', 'bob.jpg'),
('Charlie', '1992-07-10', 'M', 'charlie@example.com', 'Marseille', 'password3', 'charlie.jpg'),
('Diana', '1988-11-30', 'F', 'diana@example.com', 'Toulouse', 'password4', 'diana.jpg');

-- Insert categories
INSERT INTO emp_categorie_objet (nom_categorie) VALUES
('Esthétique'),
('Bricolage'),
('Mécanique'),
('Cuisine');

-- Insert objects
INSERT INTO emp_objet (nom_objet, id_categorie, id_membre) VALUES
-- Objects for Alice
('Rouge à lèvres', 1, 1), ('Tournevis', 2, 1), ('Clé à molette', 3, 1), ('Moulin à café', 4, 1), ('Mascara', 1, 1),
('Marteau', 2, 1), ('Pompe à huile', 3, 1), ('Mixeur', 4, 1), ('Crème hydratante', 1, 1), ('Scie', 2, 1),
-- Objects for Bob
('Fond de teint', 1, 2), ('Perceuse', 2, 2), ('Jack hydraulique', 3, 2), ('Casserole', 4, 2), ('Eyeliner', 1, 2),
('Pince', 2, 2), ('Filtre à huile', 3, 2), ('Robot pâtissier', 4, 2), ('Shampoing', 1, 2), ('Clé plate', 2, 2),
-- Objects for Charlie
('Vernis à ongles', 1, 3), ('Scie sauteuse', 2, 3), ('Bougie d allumage', 3, 3), ('Fouet de cuisine', 4, 3), ('Crayon à sourcils', 1, 3),
('Tournevis électrique', 2, 3), ('Compresseur', 3, 3), ('Cuiseur vapeur', 4, 3), ('Gel douche', 1, 3), ('Pince multiprise', 2, 3),
-- Objects for Diana
('Poudre compacte', 1, 4), ('Clé Allen', 2, 4), ('Amortisseur', 3, 4), ('Poêle', 4, 4), ('Palette de maquillage', 1, 4),
('Couteau de précision', 2, 4), ('Batterie de voiture', 3, 4), ('Machine à pain', 4, 4), ('Lotion tonique', 1, 4), ('Tournevis à cliquet', 2, 4);

-- Insert images for objects
INSERT INTO emp_image (id_objet, nom_image) VALUES
(1, 'image1.jpg'), (2, 'image2.jpg'), (3, 'image3.jpg'), (4, 'image4.jpg'), (5, 'image5.jpg'),
(6, 'image6.jpg'), (7, 'image7.jpg'), (8, 'image8.jpg'), (9, 'image9.jpg'), (10, 'image10.jpg'),
(11, 'image11.jpg'), (12, 'image12.jpg'), (13, 'image13.jpg'), (14, 'image14.jpg'), (15, 'image15.jpg'),
(16, 'image16.jpg'), (17, 'image17.jpg'), (18, 'image18.jpg'), (19, 'image19.jpg'), (20, 'image20.jpg'),
(21, 'image21.jpg'), (22, 'image22.jpg'), (23, 'image23.jpg'), (24, 'image24.jpg'), (25, 'image25.jpg'),
(26, 'image26.jpg'), (27, 'image27.jpg'), (28, 'image28.jpg'), (29, 'image29.jpg'), (30, 'image30.jpg'),
(31, 'image31.jpg'), (32, 'image32.jpg'), (33, 'image33.jpg'), (34, 'image34.jpg'), (35, 'image35.jpg'),
(36, 'image36.jpg'), (37, 'image37.jpg'), (38, 'image38.jpg'), (39, 'image39.jpg'), (40, 'image40.jpg');

-- Insert borrowings
INSERT INTO emp_emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2023-01-01', '2023-01-10'), (2, 3, '2023-01-05', '2023-01-15'), (3, 4, '2023-01-10', '2023-01-20'),
(4, 1, '2023-01-15', '2023-01-25'), (5, 2, '2023-01-20', '2023-01-30'), (6, 3, '2023-01-25', '2023-02-05'),
(7, 4, '2023-01-30', '2023-02-10'), (8, 1, '2023-02-01', '2023-02-11'), (9, 2, '2023-02-05', '2023-02-15'),
(10, 3, '2023-02-10', '2023-02-20');

CREATE OR REPLACE VIEW emp_view_objet_and_membre_and_emprunt AS 
select o.nom_objet, m.nom AS nom_personne  ,c.nom_categorie ,em.date_emprunt, em.date_retour from emp_objet o
join emp_membre m
join emp_categorie_objet c on o.id_categorie = c.id_categorie
join emp_emprunt em on o.id_objet = em.id_objet AND m.id_membre = em.id_membre;

CREATE OR REPLACE VIEW emp_view_objet_and_membre AS
select o.nom_objet, m.nom AS nom_personne, c.nom_categorie from emp_objet o
join emp_membre m
join emp_categorie_objet c on o.id_categorie = c.id_categorie


CREATE OR REPLACE VIEW emp_view_objets_complet AS
SELECT 
    o.id_objet,
    o.nom_objet,
    c.nom_categorie,
    m_prop.nom AS proprietaire,
    m_emp.nom AS emprunteur,
    e.date_emprunt,
    e.date_retour,
    CASE 
        WHEN e.date_retour IS NOT NULL AND e.date_retour >= CURDATE() THEN 'Emprunté'
        ELSE 'Disponible'
    END AS statut
FROM emp_objet o
JOIN emp_categorie_objet c ON o.id_categorie = c.id_categorie
JOIN emp_membre m_prop ON o.id_membre = m_prop.id_membre
LEFT JOIN (
    SELECT e1.* 
    FROM emp_emprunt e1
    JOIN (
        SELECT id_objet, MAX(date_retour) AS max_date 
        FROM emp_emprunt 
        GROUP BY id_objet
    ) e2 ON e1.id_objet = e2.id_objet AND e1.date_retour = e2.max_date
) e ON o.id_objet = e.id_objet
LEFT JOIN emp_membre m_emp ON e.id_membre = m_emp.id_membre;


CREATE VIEW vue_objet_complet_inner AS
SELECT 
    o.id_objet,
    o.nom_objet,
    o.id_categorie,
    o.id_membre AS id_proprietaire,
    i.id_image,
    i.nom_image,
    e.id_emprunt,
    e.id_membre AS id_emprunteur,
    e.date_emprunt,
    e.date_retour,
    CASE 
        WHEN e.date_retour IS NULL THEN 'Emprunté'
        ELSE 'Retourné'
    END AS statut_objet
FROM 
    emp_objet o
JOIN 
    emp_image i ON o.id_objet = i.id_objet
JOIN 
    emp_emprunt e ON o.id_objet = e.id_objet
    AND e.date_emprunt = (
        SELECT MAX(date_emprunt) 
        FROM emp_emprunt 
        WHERE id_objet = o.id_objet
    );


