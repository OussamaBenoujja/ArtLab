## THIS IS JUST FOR INTIAL FILES CHECK


+----------------+             +----------------+
|     User       |<----------- |     Admin      |
+----------------+  Inherits   +----------------+
| username: String|            | banUser()      |
| email: String   |            | deleteArticle()|
| password: String|            | deleteComment()|
| role: String    |            | viewLogs()     |
+----------------+             +----------------+
| register()     |
| login()        |
+----------------+
        ^
        | Writes                 +----------------+
        +----------------------> |    Article     |
                                 +----------------+
                                 | title: String  |
                                 | content: String|
                                 | author: User   |
                                 | upvotes: int   |
                                 | downvotes: int |
                                 +----------------+
                                 | upvote()       |
                                 | downvote()     |
                                 +----------------+
        ^ Comments                    ^
        |                             |
+----------------+                +----------------+
|    Comment     |                |      Log       |
+----------------+                +----------------+
| content: String|                | action: String |
| author: User   |                | timestamp: Date|
| article: Article|               | performedBy: Admin|
+----------------+                +----------------+
