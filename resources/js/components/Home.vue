<template>

        <v-container grid-list-md>
            <v-toolbar color="indigo" app>
                 <v-toolbar-title color="white--text">
                        <div class="white--text">                     
                        
                         <h2> {{ currentPerson == null ? 'Start Chating' : myFriend.name }}

                             <span style="font-size:15pt;" class="green--text lighten-text-4">
                             {{(!!typing && typers.indexOf(currentPerson) > -1) ? 'typing ..' : ''}}

                                 </span>
                             
                         </h2>
                        
                        </div>
                     </v-toolbar-title>

    <v-spacer></v-spacer>

    <v-toolbar-items>
                    <v-btn flat color="white" icon>
                      <input type="file" style="z-index: 2000; height: 40px; width: 37px;position: absolute;opacity: 0;" title="" @change="HandleImage">
                        <v-icon z-index="-2000" >attach_file</v-icon>
                    </v-btn>
                    
                    <v-btn flat icon color="success">
                        <v-icon>lens</v-icon>
                    </v-btn>

                    <v-btn flat icon color="white">
                        <v-icon>exit_to_app</v-icon>
                    </v-btn>
                    
    </v-toolbar-items>
            </v-toolbar>
            <v-layout row wrap>
                <v-flex xs3>
                <v-list dark v-if="!!people" class="mt-5">
                    <template v-for="(person,index) in people">
                    <v-list-tile :key="index" @click="assignMyFriend(person.id)">
                        <v-list-tile-content>
                            {{person.name}} {{(!!typing && typers.indexOf(person.id) > -1) ? '...' : ''}}
                        </v-list-tile-content>
        <v-icon color="success" size="15" v-show="onlineUsers.indexOf(person.id) > -1">lens</v-icon>
            </v-list-tile>
             </template>

                </v-list>
                </v-flex>
     
                <v-flex xs9 class="mt-5">
                        <v-card dark v-show="!(!!currentPerson)"  height="400" style="overflow-y:scroll;overflow-x:hidden">
                                <div class="text-xs-center">
                                    <h1> 
                                    START A NEW CHAT NOW ;)
                                </h1>
                                </div>
                        </v-card>
                    <v-card v-show="!!currentPerson" v-chat-scroll="{always: false, smooth: true}" height="400" style="overflow-y:scroll;overflow-x:hidden">
              <div class="msgs">
            <span v-for="(msg,index) in messages" :key="index">
                
              <div :style="(msg.user_id == currentUser) ? 'direction:rtl' : 'direction:ltr'" class="mx-3 mt-3">
                
                    <p :class="msg.user_id == currentUser ? 'success lighten-4 px-2' : 'indigo lighten-4 px-2'  " style="
                    max-width:200px;
                    text-align:right;
                    border-radius:7px;
                    padding:10px;  
                    width:auto;
                    min-height:30px;
                    word-wrap:break-word">
                      <v-icon left style="font-size:10pt;cursor:pointer;float:left;padding:0;position:absloute" title="copy message" v-clipboard="msg.message">file_copy</v-icon>  
                               <bdi>
                                    {{msg.message}}
                                   </bdi> 

                    <br>
                        <i class="grey--text">
                                <bdi>
                        {{ msg.created_at | moment("from") }}

                                </bdi>
                  </i>
                    </p>
                
                      <div v-show="(msg.user_id == currentUser)">
                    <v-flex xs1 offset-xs7>
                    <img
                        v-show="!!msg.img"
                        :src="`${msg.img}`"
                        style="border-radius:7px"
                        class="green lighten-2 ml-5 pa-1"
                        width="200"
                        height="150"
                        @click="showImage"
                    />     

              
                    </v-flex>
            </div>

                 <div v-show="!(msg.user_id == currentUser)">
                    <img
                        v-show="!!msg.img"
                        :src="`${msg.img}`"
                        class="grey lighten-2 ml-2"
                        width="200"
                        height="150"
                        @click="showImage"

                    />
                    
          
            </div>
              </div>
            
                   </span>
        </div>
                                
        </v-card>
        
    <v-form @submit.prevent="sendMsg" v-show="!!currentPerson" fixed>
            <v-text-field v-model="msg"  placeholder="type here" solo-inverted>
        </v-text-field>      
        </v-form>
            </v-flex>
            <v-flex xs12 offset-xs3>
                    <div class="text-xs-center">
                        <img style="border-radius:7px;width:300px" v-show="!!image" :src="image" alt="selcted img" >
                    </div>
            </v-flex>
         
               
                    
            </v-layout>

        </v-container>

</template>

<script>
import { clearTimeout, setTimeout } from 'timers';
    export default {
        data(){
                return{
                    people:null,
                    showImageDialog:false,
                    selctedImage:false,
                    currentPerson:null,
                    myFriend:null,
                    msg:'',
                    typers:[],
                    messages:[],
                    currentUser:window.Laravel.user.id,
                    typing:false,
                    typingTimer:false,
                    onlineUsers:[],
                    img:'',
                    image:null,
                    extension:null,
                }
        },
        watch:{

            currentPerson(n,o){
                this.msg = '';
                this.loadMessages(n);
            },
            msg(n,o){
                
                
                    let nLength = n.length;
                    let oLength = o.length;
            this.typingNow();
               
            
            }
            },
        created(){
            const self = this;
        this.listAllOnline();
            Echo.private('chat.'+window.Laravel.user.id)
            .listenForWhisper('typing',(tt)=> {
                    console.log('typing');
                        console.log(tt);
                    this.typing = true;
                    this.typers.indexOf(tt.typer_id) == -1 ? this.typers.push(tt.typer_id) : '';
                    clearTimeout(this.typingTimer);
                    this.typingTimer = setTimeout(() => {
                        this.typing = false;
                        let index = this.typers.indexOf(tt.typer_id);
                        this.typers.splice(index,1);    
                    }, 700);
            });


            console.log(window.Laravel.user)
            axios.get('/api/all-users',)
            .then((response)=>{
                this.people = response.data.people;
            })
            .catch((errors)=>{
                    alert('error');
                    console.log(errors.response)
            });

            },
            mounted(){
            
            Echo.private(`user.${window.Laravel.user.id}`)
            .listen('SendMessage',(e)=>{
                    console.log('eeeeeeeeeeeeeeeeeeeeeeeeee');
                    console.log(!!e.full_image_64);
                    let msg = e.msg;
                    msg.img = e.full_image_64;
                    msg.created_at =  Date.now();
                    this.messages.push(msg);
            });
      
            },
        methods:{

            showImage(e){
                    //alert(e.explicitOriginalTarget.attributes);
                    console.log(e.explicitOriginalTarget.attributes.src.nodeValue);
                    this.selctedImage = {img:e.explicitOriginalTarget.attributes.src.nodeValue,width:e.explicitOriginalTarget.naturalWidth,height:e.explicitOriginalTarget.naturalHeight};
                    this.showImageDialog = true;
            },
            sendMsg(){
               
                axios.post('/api/send-message',{
                    to:this.currentPerson,    
                    message:this.msg,
                    img:this.img,
                    full_image:this.image,
                    extension:this.extension,
                })
                .then((response)=>{
                
                this.messages.push({user_id:this.currentUser,to_id:this.currentPerson,message:this.msg,img:this.image,created_at:Date.now()});
                this.msg = '';
                  this.img='';
                  this.extension = null;
                    this.image=null;
                })
                .catch((errors)=>{
                    alert('error');
                    console.log(errors.response)
                })
            },
            loadMessages(toId){
                axios.post('/api/load-messages',{
                    to_id:toId,
                })
                .then((response)=>{
                    console.log(response.data.messages);
                   this.messages = (response.data.messages);
                })
                .catch((error)=>{
                    alert('error in loading');
                    console.log(error.response)
                })
            },
            typingNow(){
              Echo.private('chat.'+this.currentPerson)
              .whisper('typing',{
                  typing:true,
                  typer_id:window.Laravel.user.id
              });
            },

            listAllOnline(){
                Echo.join(`isIn`)
                .here((users) => {
                    console.log('all active users')
                    console.log(users);

                    //PUSH ALL ACTIVE USER
                    this.onlineUsers = users.map((val)=>{
                        return val.id
                    });
                    
                })
                .joining((user) => {
                    console.log('new user joined');
                    console.log(user);
                        this.onlineUsers.push(user.id);
                })
                .leaving((user) => {
                    console.log('left');
                    //SPLICE 
                    let index = this.onlineUsers.indexOf(user.id);
                    this.onlineUsers.splice(index,1);
                });
            },

                HandleImage(e){


             ///create file reader       
          var fr  = new FileReader();

            //get image
          fr.readAsDataURL(e.target.files[0]);

            //check mime type
          const imgs = ['image/jpeg','image/png','image/jpg'];


            //get mime type
          let imgType = e.target.files[0].type;

                //is image
          if (imgs.indexOf(imgType) == -1) {
          
            this.image =null;
            this.extension = null;
            alert('not supported');
          
          }

            else{
        
        //image loaded
        fr.onload = (e) => {
                    
            //get image extension
            this.extension = imgType.substring(imgType.indexOf('/')+1);  

            //full encoded image 64
            this.image = e.target.result;
            
            let baseStart =this.image.lastIndexOf(',') ;

            //base 64 only
            let encodedImage = this.image.substring(baseStart+1);
            
            //assign base 64 only
            this.img = encodedImage; 
            
            }

          }


    },
    assignMyFriend(id){
             this.currentPerson = id;
                let  index = this.people.findIndex((val,ind)=>{
                        return val.id == id
                });  

                this.myFriend = this.people[index];
},

        },//methods

    }
</script>

<style>

</style>
