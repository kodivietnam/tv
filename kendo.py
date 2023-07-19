from codequick import Route, Resolver, Listitem, run
from xbmcgui import DialogProgress
from json import loads
from urllib.parse import urlparse, parse_qs, unquote, unquote_plus
from os.path import basename
import xbmc, random, requests, re, sys
def getrow(row):
    return row['v'] if (row is not None) and (row['v'] is not None) else ''
@Route.register
def root(plugin, content_type="video"):
    item = Listitem()
    item.label = 'Lấy mã liên kết với trang [COLOR yellow]mi3s.top[/COLOR]'
    item.set_callback(play_video)
    yield item
@Resolver.register
def play_video(plugin):
    my_number = random.randint(10000, 99999)
    dialog = DialogProgress()
    dialog.create(f'[B][COLOR yellow]{my_number}[/COLOR][/B]', 'Đang lấy dữ liệu...')
    countdown = 200
    timeout_expired = False
    while countdown > 0:
        if dialog.iscanceled():
            dialog.close()
            sys.exit()
        resp = requests.get('https://docs.google.com/spreadsheets/d/11kmgd4cK8Kj7bJ8e8rGfYmgNxUvb1nQWN9S0y-4M3JQ/gviz/tq?gid=1028412373&headers=1')
        if f'"{my_number}"' in resp.text:
            dialog.close()
            noi = re.search(r'\{.*\}', resp.text)[0]
            m = loads(noi)
            rows = m['table']['rows']
            for row in rows:
                kenh = getrow(row['c'][1])
                ten = getrow(row['c'][2])
                if ten == my_number:
                    if kenh.startswith('http'):
                        if '&kendolastname=' in kenh:
                            match = re.search(r'kendolastname=([^&]+)', kenh)
                            lastnameok = unquote_plus(match.group(1))
                            return Listitem().from_dict(**{'label':lastnameok,'callback':kenh})
                        else:
                            return Listitem().from_dict(**{'label':unquote(basename(urlparse(kenh).path)),'callback':kenh})
            break
        else:
            countdown -= 1
            dialog.update(int(((200-countdown)/200)*100), f'Mã liên kết: [COLOR yellow][B]{my_number}[/B][/COLOR] - Thời gian chờ: [COLOR orange][B]{countdown}[/B][/COLOR] giây[CR]Đang chờ kết nối từ [COLOR yellow][B]mi3s.top[/B][/COLOR]')
            xbmc.sleep(1000)
            if countdown == 0:
                timeout_expired = True
    if timeout_expired:
        sys.exit()
    dialog.close()
if __name__ == "__main__":
    run()
