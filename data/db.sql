CREATE DATABASE artbase;
USE artbase;


CREATE TABLE Users (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(50) UNIQUE NOT NULL,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    UserType ENUM('Admin', 'Member', 'Author') NOT NULL,
    DateOfJoining DATETIME DEFAULT CURRENT_TIMESTAMP,
    Birthday DATE NOT NULL,
    Bio TEXT 
);

CREATE TABLE Articles (
    ArticleID INT PRIMARY KEY AUTO_INCREMENT,
    AuthorID INT,
    BannerImage VARCHAR(255),
    Title VARCHAR(255) NOT NULL,
    InnerText TEXT NOT NULL,
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (AuthorID) REFERENCES Users(UserID)
);

CREATE TABLE Votes (
    VoteID INT PRIMARY KEY AUTO_INCREMENT,
    ArticleID INT,
    UserID INT,
    VoteType ENUM('Upvote', 'Downvote') NOT NULL,
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ArticleID) REFERENCES Articles(ArticleID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    UNIQUE(UserID, ArticleID) 
);

CREATE TABLE AuthorDetails (
    AuthorDetailID INT PRIMARY KEY AUTO_INCREMENT,
    AuthorID INT,
    FollowersCount INT DEFAULT 0,
    FOREIGN KEY (AuthorID) REFERENCES Users(UserID)
);

CREATE TABLE Followers (
    FollowerID INT PRIMARY KEY AUTO_INCREMENT,
    MemberID INT,
    AuthorID INT,
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MemberID) REFERENCES Users(UserID),
    FOREIGN KEY (AuthorID) REFERENCES Users(UserID),
    UNIQUE(MemberID, AuthorID) 
);

CREATE TABLE Comments (
    CommentID INT PRIMARY KEY AUTO_INCREMENT,
    ArticleID INT,
    UserID INT,
    CommentText TEXT NOT NULL,
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ArticleID) REFERENCES Articles(ArticleID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE LikedArticles (
    LikedArticleID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT,
    ArticleID INT,
    CreatedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (ArticleID) REFERENCES Articles(ArticleID),
    UNIQUE(UserID, ArticleID) 
);

CREATE VIEW ArticleDetailsView AS
SELECT 
    Articles.ArticleID,
    Articles.Title,
    Articles.InnerText,
    Articles.BannerImage,
    Articles.CreatedAt AS ArticleCreatedAt,
    Users.UserID AS AuthorID,
    CONCAT(Users.FirstName, ' ', Users.LastName) AS AuthorName,
    Users.Email AS AuthorEmail,
    Votes.VoteType,
    Votes.CreatedAt AS VoteCreatedAt,
    Comments.CommentID,
    Comments.CommentText,
    Comments.CreatedAt AS CommentCreatedAt,
    Commenters.UserID AS CommenterID,
    CONCAT(Commenters.FirstName, ' ', Commenters.LastName) AS CommenterName,
    Commenters.Email AS CommenterEmail
FROM Articles
LEFT JOIN Users ON Articles.AuthorID = Users.UserID
LEFT JOIN Votes ON Articles.ArticleID = Votes.ArticleID
LEFT JOIN Comments ON Articles.ArticleID = Comments.ArticleID
LEFT JOIN Users AS Commenters ON Comments.UserID = Commenters.UserID;

ALTER TABLE Users
ADD ProfileImage VARCHAR(255) DEFAULT '../assets/img/default.jpg';


CREATE TABLE Categories (
    CategoryID INT PRIMARY KEY AUTO_INCREMENT,
    CategoryName VARCHAR(255) NOT NULL UNIQUE
);



ALTER TABLE Comments
ADD CONSTRAINT fk_article_id FOREIGN KEY (ArticleID) REFERENCES Articles(ArticleID) ON DELETE CASCADE,
ADD CONSTRAINT fk_user_id FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE;

ALTER TABLE Users ADD COLUMN ProfileImage VARCHAR(255) DEFAULT '../assets/img/default.jpg';