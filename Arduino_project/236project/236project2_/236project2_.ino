//declear the moudle is WIFI

//include function set


#include <IRremote.h>

//define overal var



//BlinkerButton Button1("btn-fan");

int swi,swi1 ,i,a,timer,timer1,timer2,b,c,btn1,btn2,btn3,btn4,light= 0;
float distance;


int PIN_RECV = D12;
IRrecv irrecv(PIN_RECV);
decode_results results;



//button1-function
void button1_callback() 
{
btn1=1;
//Blinker.print("风扇状态改变！");

Serial.print("f");
delay(1200);

}


//button2-function
void button2_callback() 
{
btn2=1;
//Blinker.print("Light状态改变！");

Serial.print("l");
delay(1200);
}


//button1-function
void button3_callback() 
{
btn3=1;
//Blinker.print("小夜灯状态改变！");

Serial.print("r");
delay(1200);
}


//button1-function
void button4_callback() 
{
btn4=1;
//Blinker.print("探照灯状态改变！");

Serial.print("b");
delay(1200);
}


//main setup function
void setup() 
{
    Serial.begin(115200);
    

  irrecv.enableIRIn();


    pinMode(D10, INPUT);  //control the ultr
    pinMode(D9, OUTPUT);  //control the ultr
        pinMode(D11, INPUT);  //control the light sensor

    


    //connect to WIFI


    //set interrupt



    

}


//main loop function
void loop() {

     if (irrecv.decode(&results)) {
    Serial.println(results.value);
    irrecv.resume();
  }

if(digitalRead(D11)!=light)
{
  if(digitalRead(D11)==1)
Serial.print("k");

if(digitalRead(D11)==0)
Serial.print("g");

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

  //     Serial.print(timer1);
  //    Serial.println();

     if(timer1<80){a++;}
}
   //    Serial.print(a);
    //  Serial.println();

  //  Blinker.print(a);
if(a>=6)
{


Serial.print("t");
delay(1200);

}
light=digitalRead(D11);

}
