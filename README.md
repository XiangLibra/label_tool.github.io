# 客製化標籤工具
上架網址:  https://xianglibra.github.io/label_tool.github.io/label_tool/label.html


## 使用方法
1. 選擇圖片
2. 點自己想要點的4個點
3. 按下 "Add annotation" 即可下載這個圖片的XML標籤檔，標籤檔的格式如下:

''' xml
<annotation>
    <size>
        <width>1920</width>
        <height>1080</height>
        <depth>3</depth>
    </size>
    <object>
        <name>1</name>
        <bndbox>
            <x0>658</x0>
            <y0>159</y0>
            <x1>352</x1>
            <y1>225</y1>
            <x2>380</x2>
            <y2>186</y2>
            <x3>628</x3>
            <y3>123</y3>
        </bndbox>
    </object>
</annotation>
'''
   

