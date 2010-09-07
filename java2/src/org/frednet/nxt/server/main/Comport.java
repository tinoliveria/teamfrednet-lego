package org.frednet.nxt.server.main;
import org.frednet.nxt.server.main.Port;
import org.frednet.nxt.server.main.config;

import java.io.IOException;
import java.util.Timer;

import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;


/**
 * Servlet implementation class Comport
 */
public class Comport extends HttpServlet {
	private static final long serialVersionUID = 1L;
    private NxtControl Nxt = null;  
    private config Config = null;
    /**
     * @see HttpServlet#HttpServlet()
     */
    public Comport() {
    	
        super();
        Nxt = new NxtControl();
        //get config
        Config = new config();
		try {
			
			//Auto detect
			for(int i = config.NXTportRange[0]; i <= config.NXTportRange[1]; i++){
				System.out.print("Test Connection on port " + "COM" + String.valueOf(i) + "\n");
				if(Nxt.Connect("COM" + String.valueOf(i))){
					boolean result = Nxt.PlayTone((short)1000,(short)1000); 
					if(result){
						System.out.print("Connection on port " + "COM" + String.valueOf(i) + "\n");
						break;
					}
				}
				Nxt = new NxtControl();
				
			}
			//interval for keepAlive and Battary
			Timer s = new Timer();
			s.scheduleAtFixedRate(Nxt, 0,config.KeepAliveInterval*1000);
			
			
		} catch (Exception e) {
		
			e.printStackTrace();
		}
        // TODO Auto-generated constructor stub
    }

	/**
	 * @see Servlet#init(ServletConfig)
	 */
	public void init(ServletConfig config) throws ServletException {
		
		
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		
		
		
		request.setAttribute("NXT.voltage",Double.toString(Nxt.BatteryVoltage));
		//TODO: error on not connected
		try{
		request.setAttribute("server.port",Nxt.communicationInterface.serialPort.getName().toString() );
		}catch(Exception e){
			request.setAttribute("server.port","<strong><span style=\"color: red;\">Warning: Not connected</span></strong>" );
		}
		request.getRequestDispatcher("/control.jsp").forward(request,response);
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		String cmd=null;
		try{ cmd = request.getParameter("cmd"); }
		catch(NumberFormatException e){}
		if(cmd!=null){
			Nxt.CommandReader(cmd);
		}
		request.setAttribute("NXT.voltage",Double.toString(Nxt.BatteryVoltage));
		try{
			request.setAttribute("server.port",Nxt.communicationInterface.serialPort.getName().toString() );
			}catch(Exception e){
				request.setAttribute("server.port","<strong><span style=\"color: red;\">Warning: Not connected</span></strong>" );
			}
		request.getRequestDispatcher("control.jsp").forward(request,response);
		
	}

}
