Put IPA files here, named after the game's slug:

    downloads/candy-crush-saga.ipa   ->  /ipa-games/puzzle/candy-crush-saga-ipa/download/

The slug is the part of the game URL before "-ipa/".
The download page picks the file up automatically, with its size and SHA-256 checksum.
To use an external link instead (CDN, cloud storage), set games.ipa_url
(and optionally games.ipa_sha256) in the database.
