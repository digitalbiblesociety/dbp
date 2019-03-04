# Bibles

### Purpose

Bibles serve as a parent wrapper for all [filesets](#filesets). This wrapping structure allows developers to pull from several different 
organizations (see assets) without introducing duplicates in their app.

### Structure

Bible **abbr** are unique while the combination of fileset **id**,**asset_id**, and **set_type_code** is unique.

```json
{
  "abbr": "CRNWBT",
  "name": "The Bible in El Nayar",
  "vname": "Ɨ nyuucari tɨ jajcua, tɨ ajta cɨme'en raxa aɨjna ɨ Tavastara'a",
  "language": "Cora, El Nayar",
  "autonym": "El Nayar",
  "language_id": 6414,
  "iso": "eng",
  "date": "1611",
  "filesets": {
    "dbs-web": [
      {
        "id": "CRNWBT",
        "type": "text_plain",
        "size": "C"
      },
      {
        "id": "CRNWBT",
        "type": "text_format",
        "size": "C"
      }
    ],
    "dbp-prod": [
      {
        "id": "CRNWYC1611",
        "type": "text_plain",
        "size": "C"
      },
      {
        "id": "CRNWBT",
        "type": "text_format",
        "size": "C"
      }
    ]
  }
}
```


### Uses

1) Bibles can be used to create a list of currently existing Bibles as seen in the 
forum of bible agencies project find.bible. See Bible Links
2) Bibles can be used to allow a user of the API to interface with different systems including the Digital Bible Library. See Bible Equivalents
3) Bibles can be used to localize your app. see Bible Translations

### References

#### Filesets
