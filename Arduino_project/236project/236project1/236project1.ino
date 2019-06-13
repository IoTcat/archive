/* *****************************************************************
 * this is the code for control unit 236
 * 
 * *****************************************************************/

//declear the moudle is WIFI
#define BLINKER_PRINT Serial
#define BLINKER_WIFI

//include function set
#include <Blinker.h>

//define overal var
char ssid[] = "236-iot";
char pswd[] = "236236236";

//announce keyname
BlinkerButton Button1("btn-fan");
BlinkerButton Button2("btn-light");
BlinkerButton Button3("btn-ledR");
BlinkerButton Button4("btn-ledB");

//declear var
int swi,swi1 ,i,a,timer,timer1,timer2,b,c= 0;
float distance;



//button1-function
void button1_callback() 
{

    digitalWrite(D5,!digitalRead(D5));
    if(digitalRead(D5)){ Blinker.print("通风结束！");}else{    Blinker.print("开始通风！");}

}


//button2-function
void button2_callback() 
{

    digitalWrite(D4,!digitalRead(D4));
    if(digitalRead(D4)){ Blinker.print("灯已打开！");}else{    Blinker.print("灯已关闭！");
    digitalWrite(D6,HIGH);
    swi=1;
    timer=millis()+20000;
    }

}


void button2_end()
{
      digitalWrite(D6,LOW);
      swi=0;
}


//button1-function
void button3_callback() 
{

    digitalWrite(D6,!digitalRead(D6));
    if(digitalRead(D6)){ Button1.print("小夜灯已打开！");}else{    Button1.print("小夜灯已关闭！");}

}


//button1-function
void button4_callback() 
{

    digitalWrite(D2,!digitalRead(D2));
    if(digitalRead(D2)){ Button1.print("探照灯已打开！");}else{    Button1.print("探照灯已关闭！");}

}


void alert()
{
    for(int i=200;i<=800;i++)
    {    
      tone(D3,i); 
      delay(5);  
    }  
    
    delay(4000);         
    for(int i=800;i>=200;i--)  
    {    
      tone(D3,i);    
      delay(10);
    }
}

//main setup function
void setup() 
{
    Serial.begin(115200);
    
    pinMode(D5, OUTPUT);  //control the fan
    pinMode(D4, OUTPUT);  //control the light
    pinMode(D6, OUTPUT);  //control the ledR
    pinMode(D2, OUTPUT);  //control the ledB

    pinMode(D10, INPUT);  //control the ultr
    pinMode(D9, OUTPUT);  //control the ultr
    pinMode(D8, INPUT);  //get the fog
    pinMode(D7, INPUT);  //get the light value
    
    pinMode(D3, OUTPUT);  //control the Alert
    
    //set the light paraperation
    digitalWrite(D5, HIGH);



    //connect to WIFI
    Blinker.begin(ssid, pswd);

    //set interrupt


    

}




//main loop function
void loop() {
    Blinker.run();

  while (Serial.available() > 0) {
    c=Serial.read();
    if (c == 'f') {
      button1_callback();
      delay(1000);
    }

     if (c== 'l') {
      button2_callback();
      delay(1000);
    }
     if (c == 'r') {
      button3_callback();
      delay(1000);
    }
     if (c == 'b') {
      button4_callback();
      delay(1000);
    }
     if (c == 't') {
          digitalWrite(D6,HIGH);
    swi=1;
    timer=millis()+20000;
    Blinker.print("床上有人，小夜灯打开！");
      delay(1000);
    }
  }




c=0;






if(swi==1)
{

  if(timer<millis())
  {
    button2_end();  Button2.print("小夜灯已关闭！");
  }
 
}

if(swi1==1)
{
  if(digitalRead(D7)){
  if(timer2<millis())
  {
        digitalWrite(D2,LOW);
      swi1=0;  Button2.print("探照灯已关闭！");
  }
   }
  else
  {
     digitalWrite(D2,LOW);
      swi1=0;  Button2.print("探照灯已关闭！");
  }
}
a=0;


for(i=0;i<50;i++)
{

    // 产生一个10us的高脉冲去触发TrigPin
        digitalWrite(D9, LOW);
        delayMicroseconds(2);
        digitalWrite(D9, HIGH);
        delayMicroseconds(10);
        digitalWrite(D9, LOW);


        distance = pulseIn(D10, HIGH) / 58.00;
 
        timer1=distance;

  //BLINKER_LOG2("Man state: ",digitalRead(D8) );

     if(timer1<100){a++;}
}
    //   Serial.print(a);
    //  Serial.println();


if(a>33)
{
  if(digitalRead(D7)){
    digitalWrite(D2,HIGH);
    swi1=1;
    timer2=millis()+80000;
    Blinker.print("探测到人，探照灯打开！");
  }
}




if(digitalRead(D8)==0)
{
  alert();
  Blinker.print("探测到烟雾，开始报警！");
}

}
