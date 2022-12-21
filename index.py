
import spotipy
from spotipy.oauth2 import SpotifyClientCredentials
import mysql.connector
import sys


mydb = mysql.connector.connect (
    host="localhost",
    user="root",
    password="",
    database="musicdic_musicdiscovery"
)

print(mydb)

mycursor = mydb.cursor()

spotify = spotipy.Spotify(client_credentials_manager=SpotifyClientCredentials
                          (client_id = 'c4af9703b81947e3a73ced319e187023' , client_secret = 'd685616c8d92463c93d728a4a2dee9c8'))

mylist = [sys.argv[1]]
#mylist = ['Metallica']


for i in mylist:
    name = i

    results = spotify.search(q='artist:' + name, type='artist')
    items = results['artists']['items']
    if len(items) > 0:
        artist = items[0]
        print(artist['uri'])

    results = spotify.artist_related_artists(artist['uri'])

    for artist in results['artists'][:30]:
        
        artist_name = artist['name']
        rank= artist['popularity']
        follower=(artist['followers']['total'])
        artist_image = artist['images'][0]['url']
        
        
        sql = """INSERT INTO spotify_artist (artist_name, rank, follower,artist_image) VALUES (%s, %s, %s, %s)"""
        val = (artist_name, rank, follower,artist_image)
        mycursor.execute(sql, val)
        print(val)
        
        mydb.commit()
        print("record inserted")
        
